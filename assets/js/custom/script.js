(function ($) {
  function showFilterActivities() {
    const tagText = $('#tag').val();
    const groeperingID = $('#groepering').val();
    const groeperingText = $('#groepering option:selected').text();
    const vrijkenmerk06ID = $('#vrijkenmerk06').val();
    const vrijkenmerk06Text = $('#vrijkenmerk06 option:selected').text();
    const dagID = $('#dag').val();
    const dagText = $('#dag option:selected').text();
    const locatieID = $('#locatie').val();
    const locatieText = $('#locatie option:selected').text();
    const ajaxUrl = 'https://cbwedamvolendam.nl/wp-admin/admin-ajax.php';

    const str =
      '&groeperingID=' +
      groeperingID +
      '&groeperingText=' +
      groeperingText +
      '&vrijkenmerk06ID=' +
      vrijkenmerk06ID +
      '&vrijkenmerk06Text=' +
      vrijkenmerk06Text +
      '&locatieID=' +
      locatieID +
      '&locatieText=' +
      locatieText +
      '&dagID=' +
      dagID +
      '&dagText=' +
      dagText +
      '&tagText=' +
      tagText +
      '&action=showFilterActivities';

    $.ajax({
      type: 'POST',
      dataType: 'html',
      url: ajaxUrl,
      data: str,

      beforeSend: function () {
        $('.loader-container').show();
      },

      success: function (data) {
        $('.loader-container').hide();
        // Do not remove database filter bad this good if you remove it explode
        const parser = new DOMParser();

        let doc;
        doc = parser.parseFromString(data, 'text/html');
        doc
          .querySelectorAll(
            '.align-items-stretch .card .card-body .popUpBox .metaInfoHolder .tableHolder table tbody tr'
          )
          .forEach((element) => {
            const tdTitle = element.childNodes[1];
            const title = tdTitle.childNodes[1].innerText;

            if (title === 'Url') {
              let urlElement = element.childNodes[3];
              let urlElementText = '';

              if (urlElement.innerText.trim().includes('https://')) {
                urlElementText = urlElement.innerText
                  .trim()
                  .replace('https://', '');
              } else if (urlElement.innerText.trim().includes('http://')) {
                urlElementText = urlElement.innerText
                  .trim()
                  .replace('http://', '');
              } else {
                urlElementText = urlElement.innerText.trim();
              }
              urlElement.innerHTML = `<a href="http://${urlElementText}" target="_blank">${urlElement.innerText.trim()}</a>`;
            }
          });

        doc.querySelectorAll('.col-lg-4').forEach((element) => {
          const title = element
            .querySelector('.card-title')
            .innerText.trim()
            .toLowerCase();

          const description = element
            .querySelector('.card-title')
            .innerText.trim()
            .toLowerCase();

          const tableDescription = element
            .querySelector('.card-body .metaInfoHolder .card-text')
            .innerText.trim()
            .toLowerCase();

          if (
            !title.includes(tagText.toLowerCase()) &&
            !description.includes(tagText.toLowerCase()) &&
            !tableDescription.includes(tagText.toLowerCase())
          ) {
            return element.remove();
          }
        });

        if (!doc.querySelector('body').children.length > 0) {
          doc.querySelector(
            'body'
          ).innerHTML = `<div class="col sb-text-center">
                                <div id="aanbodHolder">
                                    <div style="margin-top: 25px;" class="alert alert-info w-100">Er zijn geen activiteiten gevonden met de opgegeven parameters.</div>
                                </div>
                        </div>`;
        }

        $('#filterResult').html(doc.documentElement.childNodes[1].innerHTML);
      },
    });
  }

  function validateAdress() {
    const zipcode = $('#zipcode').val();
    const houseNumber = $('#housenumber').val();
    if (
      zipcode !== undefined &&
      zipcode !== '' &&
      zipcode !== null &&
      houseNumber !== undefined &&
      houseNumber !== '' &&
      houseNumber !== null
    ) {
      const root = window.location.protocol + "//" + window.location.hostname + ( window.location.hostname === "localhost" ? "/kansrijk/" : "/");
      const ajaxFile = 'https://cbwedamvolendam.nl/wp-admin/admin-ajax.php';
      const ajaxUrl = `${root}${ajaxFile}`;
      const str =
        '&zipcode=' +
        zipcode +
        '&houseNumber=' +
        houseNumber +
        '&action=validateAdress';

      $.ajax({
        type: 'POST',
        dataType: 'html',
        url: ajaxUrl,
        data: str,

        beforeSend: function () {
          // $('.loader-container').show();
        },

        success: function (data) {
          // $('.loader-container').hide();
          const response = JSON.parse(data);
          if (response.hasOwnProperty('error')) {
            $('#street').val(null);
            $('#place').val(null);
            if (response.error === false) {
              $('#zipcode').addClass('border-danger');
              $('#housenumber').addClass('border-danger');
            }
            if (response.error === "error") {
              $('#zipcode').addClass('border-danger');
              $('#housenumber').addClass('border-danger');
              console.log("Seems that something might be down...")
            }
          } else {
            $('#zipcode').removeClass('border-danger');
            $('#housenumber').removeClass('border-danger');
            $('#street').val(response.straat);
            $('#place').val(response.plaats);
          }
        },
      });
    }
  }

  $(document).on('click', '#submit_filters', function () {
    $('.popUpBox').hide();
    showFilterActivities();
    const parent = $(this).closest('.card-body');
    $(this).removeClass('active');
  });

  $(document).on('change', '#zipcode', function () {
    $('#street').val(null);
    $('#place').val(null);
    validateAdress();
  });

  $(document).on('change', '#housenumber', function () {
    $('#street').val(null);
    $('#place').val(null);
    validateAdress();
  });

  $(document).on('click', '.vertoon', function () {
    const parent = $(this).closest('.card-body');
    const popUpBox = parent.find('.popUpBox');
    const explanation = parent.find('.activityText');

    if ($(this).hasClass('active')) {
      popUpBox.slideUp();
      explanation.slideDown();
      $(this).text('Toon meer');
      $(this).removeClass('active');
    } else {
      popUpBox.slideDown();
      explanation.slideUp();
      $(this).addClass('active');
      $(this).text('Toon minder');
    }
  });
})(jQuery);
