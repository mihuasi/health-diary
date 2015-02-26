$(function(){

    var slideTo;
    //TODO set this at global level
    var urlbase = '/health-diary';
    
    function setup() {
        $.datepicker.setDefaults({
            showOn: "both",
            buttonImageOnly: true,
            buttonImage: "http://localhost:8888/ci/images/calendar.png",
            buttonText: "Calendar"
        });
        $('#datepicker').datepicker({dateFormat: 'dd/mm/yy', onSelect: function() {
            $(this).change();}});

        $('#datepicker').change(function() {
            var formChangeDateSuccess = function(data) {
                $('.newEntryFormContainer').empty();
                $('.newEntryFormContainer').append(data);
                setup();
            }
            var selectedDate = $(this).val();
            $.ajax({
                type: "POST",
                url: urlbase + '/tracker/refreshForm',
                data : {date : selectedDate},
                success: formChangeDateSuccess,
                dataType: 'json'
            });
        });
        $('.editEntry').click(function(){
            var clickedDate = $(this).data('date');
            clickedDate = mySqltoJsDate(clickedDate);
            $('#datepicker').datepicker("setDate", clickedDate);
            $('#datepicker').change();
            $("html, body").animate({ scrollTop: 0 }, 400);


        });
        var taggable = $('.use-tags ol');
        taggable.each(function( index ) {
           var suggestions = $(this).data('suggestions');
           if (suggestions.length) {
               suggestionsArr = suggestions.split('|');
           } else {
               suggestionsArr = new Array();
           }
           $(this).tagit({
                availableTags: suggestionsArr
            });
        });
        $('ol.readOnlyTags').tagit({
                readOnly: true
        });

        $('#submitEntry').click(function(){
            $('.information p, .information .closeInfo').hide();

            var fields = $('.newEntryFormContainer .tagit-hidden-field');
            var tags = new Array();
            fields.each(function( index ) {
                var parentOl = $(this).parent().parent();
                var attributeId = parentOl.attr('id');
                var key = attributeId + '/' + index;
                $(this).attr('name', key);
                tags.push({'key': key, 'value':$(this).val()});
            });
            var ratings = $('select.rating');
            var ratingVals = new Array();

            ratings.each(function() {
                var optionSelected = $('option:selected', $(this));
                var value = optionSelected.val();
                var name = $(this).attr('name');
                var nameSplit = name.split('-');
                ratingVals.push({'key': nameSplit[2], 'value': value, 'type': nameSplit[1]});
            });

            var date = $('#tracker_new_entry .hasDatepicker').val();
            var formRefreshSuccess = function(data) {
                var isNewEntry = $('.newEntryFormContainer .textualDate').data('isnew');
                var date = $('#datepicker').val();
                var slideTo = 'slide-to-' + date;
                var slideToTag = $('ul[name="' + slideTo + '"]');
                $('.information span.messageDate').text(date);
                if (isNewEntry) {
                    $('.information p.newEntryMessage').fadeIn();
                } else {
                    $('.information p.editEntryMessage').fadeIn();
                }
                $('.information .closeInfo').fadeIn();
                $('.information a.messageLink').click(function(event){
                    event.preventDefault();
                    $('html,body').animate({scrollTop: slideToTag.offset().top});
                });
                $('.information .closeInfo').click(function(){
                    $('.information p, .information .closeInfo').fadeOut();
                });
                $('.newEntryFormContainer').empty();
                $('.newEntryFormContainer').append(data);
                setup();
            }
            var newDayLoadedSuccess = function(data) {
                $('.previousDaysContainer').empty();
                $('.previousDaysContainer').append(data);
                setup();
            }
            var onSuccess = function(data) {
                $.ajax({
                    type: "POST",
                    url: urlbase + '/tracker/addNewDayToPrevious',
                    success: newDayLoadedSuccess,
                    dataType: 'json'
                });
                $.ajax({
                    type: "POST",
                    url: urlbase + '/tracker/refreshForm',
                    success: formRefreshSuccess,
                    dataType: 'json'
                });
            }
            $.ajax({
                type: "POST",
                url: urlbase + '/tracker/processNewEntry',
                data: {'tags' : tags, 'ratings' : ratingVals, 'date' : date},
                success: onSuccess,
                dataType: 'json'
              });
        });
    }

    setup();

    function mySqltoJsDate(date) {
        var dateSplit = date.split('-');
        var dateYear = parseInt(dateSplit[0]);
        // JS uses zero-based month numbering
        var dateMonth = parseInt(dateSplit[1]) - 1;
        var dateDay = parseInt(dateSplit[2]);

        var dateJs = new Date(dateYear, dateMonth, dateDay);
        return dateJs;
    }
});