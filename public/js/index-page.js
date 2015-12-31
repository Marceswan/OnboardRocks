var autocomplete;

function initAutocomplete() {
  // Create the autocomplete object, restricting the search to geographical
  // location types.
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {!HTMLInputElement} */(document.getElementById('pac-input')),
      {types: ['address'],componentRestrictions: {country: "us"}});
  
  autocomplete.addListener('place_changed', function() {
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      window.alert("Google does not recognize that address.  Select an address from the dropdown.  US addresses only.");
      return;
    } else {
        SearchProperty(place.address_components,place.geometry.location.lat(),place.geometry.location.lng());
    }
  });
}

function SearchProperty(components,lat,lng) {
    var street_number, street, city, state, zip_code;
    
    for (var i = 0, component; component = components[i]; i++) {
        console.log(component);
        if (component.types[0] == 'street_number') {
            street_number = component['long_name'];
        }
        if (component.types[0] == 'route') {
            street = component['long_name'];
        }
        if (component.types[0] == 'locality') {
            city = component['long_name'];
        }
        if (component.types[0] == 'administrative_area_level_1') {
            state = component['long_name'];
        }
        if (component.types[0] == 'postal_code') {
            zip_code = component['long_name'];
        }
    }
    window.location.href = "../property-records/search?address1=" + street_number + " " + street + "&address2=" + city + ", " + state  + " " + zip_code + "&radius=.01&lat=" + lat + "&lng=" + lng+"&page=1";
}

