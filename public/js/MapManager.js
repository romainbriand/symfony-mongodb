/* global google, MarkerClusterer */

/**
 * MapManager
 * Manages map of restaurants and markers interactions
 *
 * @author Romain Briand <contact@romain-briand.fr>
 */
class MapManager
{
    map;

    /** @type Element */
    mapContainer = document.querySelector("#map");

    /** @type array */
    points;

    /** @type array */
    markers = [];

    /** @type int Initial map center */
    initialCenter = {
        lat: 43.2392,
        lng: 5.3696
    };

    /** @type int Initial map zoom */
    initialZoom = 12;

    /**
     * MapManager constructor
     */
    constructor()
    {
        this.plugEvents();
    }

    /**
     * Plug events listeners
     */
    plugEvents()
    {
        this.mapContainer.addEventListener("geoPointsFetched", this.generateMarkers.bind(this));
        this.mapContainer.addEventListener("markersGenerated", this.generateCluster.bind(this));
    }

    /**
     * Initializes the map centered on Marseilles, France
     *
     * @returns void
     */
    initializeMap()
    {
        this.map = new google.maps.Map(this.mapContainer, {
            center: this.initialCenter,
            zoom: this.initialZoom
        });
    }

    /**
     * Fetch geo points
     *
     * @returns {Promise<void>}
     */
    async fetchGeoPoints()
    {
        let points = await fetch("/points");
        this.points = await points.json();

        const event = new CustomEvent("geoPointsFetched");
        this.mapContainer.dispatchEvent(event);
    }

    /**
     * Display markers on the map
     *
     * @returns void
     */
    generateMarkers()
    {
        this.points.map((point, index) => {
            let marker = new google.maps.Marker({
                position: new google.maps.LatLng(point.coordinates.latitude, point.coordinates.longitude),
                label: (index+1).toString()
            });
            this.markers.push(marker);
        }, this);

        const event = new CustomEvent("markersGenerated");
        this.mapContainer.dispatchEvent(event);
    }

    /**
     * Generate clusters for markers
     *
     * @returns void
     */
    generateCluster()
    {
        new MarkerClusterer(this.map, this.markers, {
            imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
        });
    }
}