$(function () {
    var key = ''; // Put your Google Maps API Key here
    var locale = $('html').attr('lang') || 'ar';
    var url = 'https://maps.googleapis.com/maps/api/js?libraries=places&language=' + locale + (key ? '&key=' + key : '');

    $.getScript(url, function () {
        $('[data-google-map-picker]').each(function () {
            var $this = $(this);
            var $lat = $($this.data('lat-input') || '#latitude');
            var $lng = $($this.data('lng-input') || '#longitude');
            var $search = $($this.data('search-input') || '#map-search');

            var lat = parseFloat($lat.val()) || 24.7136;
            var lng = parseFloat($lng.val()) || 46.6753;
            var center = { lat: lat, lng: lng };

            var map = new google.maps.Map(this, {
                center: center,
                zoom: $lat.val() ? 15 : 12,
                mapTypeControl: false,
                streetViewControl: false
            });

            var marker = new google.maps.Marker({
                position: center,
                map: map,
                draggable: true
            });

            function setCoords(pos) {
                $lat.val(pos.lat().toFixed(7));
                $lng.val(pos.lng().toFixed(7));
            }

            map.addListener('click', function (e) {
                marker.setPosition(e.latLng);
                setCoords(e.latLng);
            });

            marker.addListener('dragend', function (e) {
                setCoords(e.latLng);
            });

            if ($search.length) {
                var autocomplete = new google.maps.places.Autocomplete($search[0]);
                autocomplete.bindTo('bounds', map);
                autocomplete.addListener('place_changed', function () {
                    var place = autocomplete.getPlace();
                    if (!place.geometry || !place.geometry.location) return;
                    map.panTo(place.geometry.location);
                    map.setZoom(16);
                    marker.setPosition(place.geometry.location);
                    setCoords(place.geometry.location);
                });
                $search.on('keydown', function (e) {
                    if (e.key === 'Enter') e.preventDefault();
                });
            }
        });
    });
});
