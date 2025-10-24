// Map initialization and localisations display
window.addEventListener('load', function() {
    // Check if map element exists on the page
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        return;
    }

    // Get API endpoint from data attribute
    const apiEndpoint = mapElement.dataset.apiEndpoint;
    if (!apiEndpoint) {
        console.error('API endpoint not specified');
        return;
    }

    // Initialize the map centered on Ukraine
    const map = L.map('map').setView([49.0, 32.0], 6);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);


    // Appeller le controller CarnetController pour obtenir les posts en JSON
    fetch(apiEndpoint)
        .then(response => response.json())
        .then(localisations => {
            console.debug('Localisations reçues:', localisations);
            if (localisations.length === 0) {
                console.warn('Aucune localisation dans la BD');
                return;
            }

            console.log (localisations);
            localisations.forEach(localisation => {
                // Parse and validate coordinates
                const lat = parseFloat(localisation.latitude);
                const lng = parseFloat(localisation.longitude);
                if (Number.isFinite(lat) && Number.isFinite(lng)) {
                    const marker = L.marker([lat, lng]).addTo(map);



                    // Simple popup with name
                    marker.bindPopup(`
                        <div class="marker-popup">
                            <h5>${localisation.carnetTitre}</h5>
                            <h6>${localisation.titre}</h6>
                            ${localisation.category ? `<span class="category-badge">${localisation.category}</span><br>` : ''}
                            ${localisation.photo ? `<img src="${localisation.photo}" class="localisation-image">` : ''}
                            <button class="btn btn-primary btn-sm mt-2" onclick="window.location.href = '/carnet/${localisation.carnetId}/#post-${localisation.id}';">
                                Voir
                            </button>
                        </div>
                    `);
                } else {
                    console.warn('Coordonnées invalides pour la localisation', localisation);
                }
            });

            // Adjust view to show all localisations
            const markers = [];
            Object.values(map._layers).forEach(layer => {
                if (layer instanceof L.Marker) {
                    markers.push(layer);
                }
            });

            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        })
        .catch(error => {
            console.error('Erreur de chargement des posts:', error);
        });
});
