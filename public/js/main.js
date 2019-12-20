"use strict";

async function initialize()
{
    let mapManager = new MapManager();
    mapManager.initializeMap();
    await mapManager.fetchGeoPoints();
}