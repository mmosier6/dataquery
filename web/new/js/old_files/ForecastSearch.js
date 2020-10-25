
var inputfield = false;
var feedbackdiv = false;


$(document).ready(function(){

    $("#btnSearch").click(function(){
        $("#txtError").css("display", "none");

        getForecastResults($("#inputstring"), $("#txtError"));
        return false;
    });
    $("#inputstring").maxLength = 120;
    
    $("#inputstring").blur(function()
    {
        if(this.value=='')
        {
            this.style.color='#999999'; 
            this.value='Enter location ...';
        }
    });
    $("#inputstring").focus(function()
    {
        if(this.value=='Enter location ...')
        {
            this.value=''; 
            this.style.color = '#000000';
        }
    });
    $("#btnCloseError").click(function(){
       $('#txtError').css('display', 'none'); 
    });
});

function getForecastResults(objInput, txtError)
{
    feedbackdiv = txtError;
    inputfield = objInput;
    
    myfcstGeocoder = new google.maps.Geocoder();
    myfcstGeocoder.geocode( { 'address': objInput.val(), 'region': 'us'}, 
        function(results, status)
        {
            if (status == google.maps.GeocoderStatus.OK) 
            {
                if (results.length > 1) //More than 1 result
                {
                    feedbackdiv.css('display', 'block');
                    $(feedbackdiv).find("#errorNoResults").css('display', 'none');
                    $(feedbackdiv).find("#errorMultipleResults").css('display', 'block');
                    $(feedbackdiv).find("#errorChoices").css('display', 'block');
                    $(feedbackdiv).find("#btnCloseError").css('display', 'block');
                    var choiceText = "";
                    var innerText = "";
                    var href = ""
                    var choices = $(feedbackdiv).find("#errorChoices");
                    for (var i=0; i < results.length; i++) 
                    {
                        href = getPointLink(results[i].geometry.location, results[i].geometry.location) + "&searchresult=" + escape(results[i].formatted_address);
                        innerText = results[i].formatted_address;
                        
                        link = '<a href="' + href + '">' + innerText + '</a>';
                        choiceText += link + "<br />";
                        choices.html(choiceText);
                    }
                }
                else // One result was found
                {
                    var form = inputfield.closest("form");
                    link = getPointLink(results[0].geometry.location, results[0].geometry.location) + "&searchresult=" + escape(results[0].formatted_address);
                    form.attr("action", link);
                    form.attr("method", 'post');
                    form.submit();
                }
            }
            else
            {
                feedbackdiv.css('display', 'block');
                $(feedbackdiv).find("#errorNoResults").css('display', 'block');
                $(feedbackdiv).find("#errorMultipleResults").css('display', 'none');
                $(feedbackdiv).find("#errorChoices").css('display', 'none');
                $(feedbackdiv).find("#btnCloseError").css('display', 'block');
            } 
        }
    );
}

function getPointLink(location)
{
    lat = location.lat();
    lon = location.lng();
    var list='?lat='+lat+'&lon='+lon+'&site='+'all'+'&smap=1';
    return 'http://forecast.weather.gov/MapClick.php'+list;
}
