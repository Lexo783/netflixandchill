var chilldrens = $( ".rating" ).children();
var isChek = false
var rating = null;
getRating()
function sendRating(value,movie,path)
{
    $.ajax({
        url: path,
        method: 'POST',
        data: {
            rate: value,
            movieId: movie,
        },
        success: function ()
        {
            console.log('OK')
        }
    });
}

function getRating(movie,path)
{
    $.ajax({
        url: path,
        method: 'POST',
        data: {
            movieId: movie,
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

function toggleFavorite(value, path, movieId)
{
    $.ajax({
        url: path,
        method: 'POST',
        data: {
            favorite: value,
            movieId: movieId,
        },
        success: function (data)
        {
            $( "#favoriteDiv" ).load(window.location.href + " #favoriteDiv" );
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
