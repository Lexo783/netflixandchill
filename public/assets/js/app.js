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

function searchBar(title)
{
    var url = "/"
    if (title != '')
    {
        $('#resultAjax').empty();
        $.ajax({
            url: '/result',
            method: 'POST',
            data: {
                title: title
            },
            success: function (movies)
            {
                $('#resultAjax').addClass('row')
                $.each(movies,function (index,value){
                    $('<div class="col-3">\n' +
                        '    <div class="position_result">\n' +
                        '        <div class="item"  style="width: 135px" onclick="window.location.href = \'/movies/show/'+value.id+'\'">\n' +
                        '            <div class="item__image">\n' +
                        '                <img src="assets/movies/'+value.picture+'" style="width: 135px; height: 202.5px" />\n' +
                        '            </div>\n' +
                        '            <div class="item__body">\n' +
                        '                <p class="title-movie">'+value.title+'</p>\n' +
                        '                <div class="item__more__information" id="">\n' +
                        '                    <img src="assets/img/flecheBas.png" title="Plus d\'info" style="width:32px; height:32px; margin: auto" />\n' +
                        '                </div>\n' +
                        '                <div class="item__description" style="display: none;">\n' +
                        '\n' +
                        '                </div>\n' +
                        '            </div>\n' +
                        '        </div>\n' +
                        '    </div>\n' +
                        '</div>').appendTo('#resultAjax');

                })
            }
        });
    }
    else {
        document.location.assign(url)
    }
}
