<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Martian Republic - Logbook</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="The Marscoin Foundation">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/css/simplemde.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/upload.css">
    <link rel="stylesheet" href="/assets/wallet/css/dropify.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">

<style>
      #viewDiv {
        padding: 0;
        margin: 0;
        height: 100%;
        width: 100%;
      }
    </style>

<link rel="stylesheet" href="https://js.arcgis.com/4.29/esri/themes/light/main.css">
<script src="https://js.arcgis.com/4.29/"></script>

    <script>
require([
        "esri/config",
        "esri/Map",
        "esri/views/SceneView",
        "esri/layers/ElevationLayer",
        "esri/layers/TileLayer",
        "esri/layers/FeatureLayer",
        "esri/widgets/LayerList"
      ], (esriConfig, Map, SceneView, ElevationLayer, TileLayer, FeatureLayer, LayerList) => {
        const marsElevation = new ElevationLayer({
          url:
            "https://astro.arcgis.com/arcgis/rest/services/OnMars/MDEM200M/ImageServer",
          copyright:
            "NASA, ESA, HRSC, Goddard Space Flight Center, USGS Astrogeology Science Center, Esri"
        });

        const marsImagery = new TileLayer({
          url:
            "https://astro.arcgis.com/arcgis/rest/services/OnMars/MDIM/MapServer",
            title: "Imagery",
          copyright: "USGS Astrogeology Science Center, NASA, JPL, Esri"
        });

        esriConfig.apiKey = "AAPK07a9ad09ed8d4edb9071d999fc57f0c4k09kruovS84CkJWmNpaTcW5gwbhC8ZzxelS2TBuYkUCLpqPPCI2U8I0pUubwdKe7";

        const map = new Map({
          ground: {
            layers: [marsElevation]
          },
          layers: [marsImagery]
        });

        const view = new SceneView({
          map: map,
          container: "viewDiv",
          qualityProfile: "high",
          // setting the spatial reference for Mars_2000 coordinate system
          spatialReference: {
            wkid: 104971
          },
          camera: {
            position: {
              x: 27.63423,
              y: -6.34466,
              z: 1281525.766,
              spatialReference: 104971
            },
            heading: 332.28,
            tilt: 37.12
          }
        });

        const cratersLayer = new FeatureLayer({
          url:
            "https://services.arcgis.com/P3ePLMYs2RVChkJx/arcgis/rest/services/Mars_Nomenclature_Mountains/FeatureServer",
          definitionExpression: "type = 'Crater, craters'",
          title: "Craters",
          renderer: {
            type: "simple",
            symbol: {
              type: "polygon-3d",
              symbolLayers: [
                {
                  type: "fill",
                  material: { color: [255, 255, 255, 0.1] },
                  outline: {
                    color: [0, 0, 0, 0.4],
                    size: 2
                  }
                }
              ]
            }
          },
          labelingInfo: [
            {
              labelPlacement: "above-center",
              labelExpressionInfo: { expression: "$feature.NAME" },
              symbol: {
                type: "label-3d",
                symbolLayers: [
                  {
                    type: "text",
                    material: {
                      color: [0, 0, 0, 0.9]
                    },
                    halo: {
                      size: 2,
                      color: [255, 255, 255, 0.7]
                    },
                    font: {
                      size: 10
                    }
                  }
                ],
                verticalOffset: {
                  screenLength: 40,
                  maxWorldLength: 500000,
                  minWorldLength: 0
                },
                callout: {
                  type: "line",
                  size: 0.5,
                  color: [255, 255, 255, 0.9],
                  border: {
                    color: [0, 0, 0, 0.3]
                  }
                }
              }
            }
          ]
        });

        map.add(cratersLayer);

        const shadedReliefLayer = new TileLayer({
          url:
            "https://astro.arcgis.com/arcgis/rest/services/OnMars/MColorDEM/MapServer",
          copyright: "USGS Astrogeology Science Center, NASA, JPL, ESA, DLR, Esri",
          title: "Shaded relief",
          visible: true
        });

        map.add(shadedReliefLayer);

        const layerList = new LayerList({
          view
        });

        view.ui.add(layerList, "top-right");

      });
    </script>
  </head>


  
  <body>
    <div id="viewDiv"></div>
  </body>
</html>