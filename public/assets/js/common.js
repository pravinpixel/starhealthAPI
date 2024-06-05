"use strict";

toastr.options = {
  "progressBar": true,
};
 $("#date").flatpickr();
var Commonstep =function(){
  $("#close1,#next1,#draft1").click(function () {
    var type = $(this).val(); 
    $('#type1').val(type);

  });
  $("#close2,#next2,#draft2").click(function () {
    var type = $(this).val(); 
    $('#type2').val(type);

  });
  $("#close3,#next3,#draft3").click(function () {
    var type = $(this).val(); 
    $('#type3').val(type);

  });
  $("#close4,#next4,#draft4").click(function () {
    var type = $(this).val(); 
    $('#type4').val(type);

  });
  $("#close5,#next5,#draft5").click(function () {
    var type = $(this).val(); 
    $('#type5').val(type);

  });
  $("#close6,#next6,#draft6").click(function () {
    var type = $(this).val(); 
    $('#type6').val(type);

  });
  $("#close7,#next7,#draft7").click(function () {
    var type = $(this).val(); 
    $('#type7').val(type);

  });

  $(".preview").click(function () { 
  var bn = $(this).attr('id');
   const preview = document.querySelector(".preview");
   preview.setAttribute('data-kt-indicator', 'on');
   setTimeout(function() {
      $("a."+bn)[0].click();
      preview.removeAttribute('data-kt-indicator');
    }, 50); 
  
  });
  $(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
}();
var selectedDropdown =function(){
  // ..country..state..city
  $('#countryDropdown').on('change', function () {
    var selectedCountry = $(this).val();
          
    if (selectedCountry) {
      $.ajax({
        url: CountryByState,
        method: 'GET',
        data: { country: selectedCountry },
        success: function (response) {
          $('#stateDropdown').empty().append($('<option>', {
            value: '',
            text: 'Choose State'
          }));
          $('#cityDropdown').empty().append($('<option>', {
        value: '',
        text: 'Choose City'
          }));

          $.each(response.states, function (index, state) {
            $('#stateDropdown').append($('<option>', {
              value: state.id,
              text: state.name
            }));
          });
        },
        error: function (error) {
          console.error('Error retrieving states:', error);
        }
      });
    } else {
      $('#stateDropdown').empty().append($('<option>', {
        value: '',
        text: 'Choose State'
      }));
      $('#cityDropdown').empty().append($('<option>', {
        value: '',
        text: 'Choose City'
      }));
    }
  });
  $('#stateDropdown').on('change', function () {
    var selectedState = $(this).val();

    if (selectedState) {
      $.ajax({
        url: StateByCity,
        method: 'GET',
        data: { state: selectedState },
        success: function (response) {
          $('#cityDropdown').empty().append($('<option>', {
            value: '',
            text: 'Choose City'
          }));
          $.each(response.cities, function (index, city) {
            $('#cityDropdown').append($('<option>', {
              value: city.id,
              text: city.name
            }));
          });
        },
        error: function (error) {
          console.error('Error retrieving cities:', error);
        }
      });
    } else {
      $('#cityDropdown').empty().append($('<option>', {
        value: '',
        text: 'Choose City'
      }));
    }
  });
}();
var selectedDropdown1 =function(){
  // ..country..state..city
  $('#countryDropdown1').on('change', function () {
    var selectedCountry = $(this).val();
          
    if (selectedCountry) {
      $.ajax({
        url: CountryByState,
        method: 'GET',
        data: { country: selectedCountry },
        success: function (response) {
          $('#stateDropdown1').empty().append($('<option>', {
            value: '',
            text: 'Choose State'
          }));
          $('#cityDropdown1').empty().append($('<option>', {
        value: '',
        text: 'Choose City'
          }));

          $.each(response.states, function (index, state) {
            $('#stateDropdown1').append($('<option>', {
              value: state.id,
              text: state.name
            }));
          });
        },
        error: function (error) {
          console.error('Error retrieving states:', error);
        }
      });
    } else {
      $('#stateDropdown1').empty().append($('<option>', {
        value: '',
        text: 'Choose State'
      }));
      $('#cityDropdown1').empty().append($('<option>', {
        value: '',
        text: 'Choose City'
      }));
    }
  });
  $('#stateDropdown1').on('change', function () {
    var selectedState = $(this).val();

    if (selectedState) {
      $.ajax({
        url: StateByCity,
        method: 'GET',
        data: { state: selectedState },
        success: function (response) {
          $('#cityDropdown1').empty().append($('<option>', {
            value: '',
            text: 'Choose City'
          }));
          $.each(response.cities, function (index, city) {
            $('#cityDropdown1').append($('<option>', {
              value: city.id,
              text: city.name
            }));
          });
        },
        error: function (error) {
          console.error('Error retrieving cities:', error);
        }
      });
    } else {
      $('#cityDropdown1').empty().append($('<option>', {
        value: '',
        text: 'Choose City'
      }));
    }
  });
}();
