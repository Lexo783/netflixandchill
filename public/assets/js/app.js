var chilldrens = $( ".rating" ).children();
var isChek = false
var rating = null;
getRating()
function sendRating(value,path)
{
    $.ajax({
        url: path,
        method: 'POST',
        data: {
            rate: value,
            movieId: 1,
        },
        success: function ()
        {
            console.log('OK')
        }
    });
}

function getRating(path)
{
    $.ajax({
        url: path,
        method: 'POST',
        data: {
            movieId: 1,
        },
        success: function (result)
        {
            rating = JSON.stringify(result);
            for (var i = 0; i < chilldrens.length; i++)
            {
                if(chilldrens[i].id <= rating)
                {
                    $(chilldrens[i]).addClass("checkedClick");
                    $(this).addClass("checkedClick");
                }
                if(chilldrens[i].id > rating){
                    $(chilldrens[i]).removeClass("checkedClick")
                    $(chilldrens[i]).removeClass("checked")
                }
            }
        }
    });
}

$(chilldrens).hover(
    function (){
        for (var i = 0; i < chilldrens.length; i++)
        {
            if(chilldrens[i].id <= $(this).attr('id'))
            {
                $(chilldrens[i]).addClass("checked")
            }
        }
    },
    function() {
        for (var i = 0; i < chilldrens.length; i++)
        {
            if(!$(this).hasClass("checkedClick"))
            {
                $(chilldrens[i]).removeClass("checked")
            }
        }
    }
)
$('.fa-star').click(
    function (){
        for (var i = 0; i < chilldrens.length; i++)
        {
            if(chilldrens[i].id <= $(this).attr('id'))
            {
                $(chilldrens[i]).addClass("checkedClick");
                $(this).addClass("checkedClick");
            }
            if(chilldrens[i].id > $(this).attr('id')){
                $(chilldrens[i]).removeClass("checkedClick")
                $(chilldrens[i]).removeClass("checked")
            }
        }
    }
)
