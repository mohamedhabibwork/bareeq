<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            {{ Form::label(__('main.name'), null, ['class' => 'control-label']) }}
            {{ Form::text('name', old('name'), array_merge(['class' => 'form-control  '.($errors->has('name') ? 'is-invalid' : '')],['required'=>true])) }}
            <x-dashboard.error name="name"/>
        </div>
    </div>

    {!! Form::hidden('locations',$model?->locations_text,['id'=>'locations']) !!}
    <div class="col-md-12">
        <div class="btn-group btn-group-md" >
            <button type="button" class="btn btn-primary" onclick="clearMap()">Clear</button>
            {{--        <button type="button" class="btn btn-primary" onclick="plotWKT()">Plot Shape</button>--}}
        </div>
    </div>
    <div id="map" style="width: 100%;height:550px;"></div>

    @push('js')

        <link rel="stylesheet" href="https://openlayers.org/en/v6.14.1/css/ol.css" type="text/css">
        <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
        <script
            src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
        <script
            src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/build/ol.js"></script>
        <script>
            var raster;
            var source;
            var vector;
            var map;
            var typeSelect;
            var draw;
            var features = new ol.Collection();
            var format = new ol.format.WKT();
            var current_shape = "Polygon";

            var fill = new ol.style.Fill({
                color: 'rgba(210, 122, 167,0.2)'
            });
            var stroke = new ol.style.Stroke({
                color: '#B40404',
                width: 2
            });
            const MyElement = document.getElementById('locations');
            var styles = [
                new ol.style.Style({
                    image: new ol.style.Circle({
                        fill: fill,
                        stroke: stroke,
                        radius: 5
                    }),
                    fill: fill,
                    stroke: stroke
                })
            ];

            function addInteraction(shape) {
                draw = new ol.interaction.Draw({
                    features: features,
                    type: /** @type {ol.geom.GeometryType} */ shape
                });
                map.addInteraction(draw);
            }

            /**
             * Let user change the geometry type.
             * @param {Event} e Change event.
             */
            function createVector() {
                vector = new ol.layer.Vector({
                    source: new ol.source.Vector({features: features}),
                    style: styles
                });
            }

            function toEPSG4326(element, index, array) {
                element = element.getGeometry().transform('EPSG:3857', 'EPSG:4326');
            }

            function toEPSG3857(element, index, array) {
                element = element.getGeometry().transform('EPSG:4326', 'EPSG:3857');
            }

            function selectGeom(shape) {
                current_shape = shape;
                map.removeInteraction(draw);
                addInteraction(shape);
            }

            function init() {
                createVector();
                raster = new ol.layer.Tile({
                    source: new ol.source.OSM()
                });
                const clearMap = function () {
                    if(MyElement.value !== ""){
                        features.removeAt(0)
                    }
                }
                features.on("add", function (e) {
                    clearMap()
                    restoreDefaultColors();
                    features.forEach(toEPSG4326);
                    MyElement.value = format.writeFeatures(features.getArray(), {rightHanded: true});
                    features.forEach(toEPSG3857);
                });

                map = new ol.Map({
                    layers: [raster, vector],
                    target: 'map',
                    view: new ol.View({
                        center: [-11000000, 4600000],
                        zoom: 4
                    })
                });
                map.on('drawstart',clearMap)
                vector.on('drawstart',clearMap)

                if (window.location && window.location.hash) {
                    loadWKTfromURIFragment(window.location.hash);
                }
                plotWKT();
                selectGeom('Polygon');
            }

            function restoreDefaultColors() {
                MyElement.style.borderColor = "";
                MyElement.style.backgroundColor = "";
            }

            // Plot wkt string on map
            function plotWKT() {
                var new_feature;

                wkt_string = MyElement.value;
                if (wkt_string == "") {
                    MyElement.style.borderColor = "red";
                    MyElement.style.backgroundColor = "#F7E8F3";
                    return;
                } else {
                    try {
                        new_feature = format.readFeature(wkt_string);
                    } catch (err) {
                    }
                }

                if (!new_feature) {
                    MyElement.style.borderColor = "red";
                    MyElement.style.backgroundColor = "#F7E8F3";
                    return;
                } else {
                    map.removeLayer(vector);
                    features.clear();
                    new_feature.getGeometry().transform('EPSG:4326', 'EPSG:3857');
                    features.push(new_feature);
                }
                vector = new ol.layer.Vector({
                    source: new ol.source.Vector({features: features}),
                    style: styles
                });

                selectGeom(current_shape);
                map.addLayer(vector);
                derived_feature = features.getArray()[0];
                extent = derived_feature.getGeometry().getExtent();
                minx = derived_feature.getGeometry().getExtent()[0];
                miny = derived_feature.getGeometry().getExtent()[1];
                maxx = derived_feature.getGeometry().getExtent()[2];
                maxy = derived_feature.getGeometry().getExtent()[3];
                centerx = (minx + maxx) / 2;
                centery = (miny + maxy) / 2;
                map.setView(new ol.View({
                    center: [centerx, centery],
                    zoom: 8
                }));
                map.getView().fit(extent, map.getSize());
            }

            function clearMap() {
                map.removeLayer(vector);
                features.clear();
                vector = new ol.layer.Vector({
                    source: new ol.source.Vector({features: features}),
                    style: styles
                });
                selectGeom(current_shape);
                map.addLayer(vector);
                MyElement.value = "";
                restoreDefaultColors();
            }

            function loadWKTfromURIFragment(fragment) {
                // remove first character from fragment as it contains '#'
                var wkt = window.location.hash.slice(1);
                MyElement.value = decodeURI(wkt);
            }
        </script>
        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <style>
            @media only screen and (min-width: 1366px) {
                .map {
                    height: 400px !important
                }
            }

            @media only screen and (min-width: 1440px) {
                .map {
                    height: 500px !important
                }
            }

            @media only screen and (min-width: 1680px) {
                .map {
                    height: 650px !important
                }
            }

            @media only screen and (min-width: 1920px) {
                .map {
                    height: 700px !important
                }
            }

            @media only screen and (min-width: 2560px) {
                .map {
                    height: 1050px !important
                }
            }
        </style>
    @endpush

</div>
