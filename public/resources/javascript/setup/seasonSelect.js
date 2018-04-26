/*
	Select dropdown for Season select
*/

var $ = require('jquery')

$( '#selectSeason' ).change(function() {

  // Alter innerHTML of div container
   
   var episodes = $('#episodes').data("episodes") ;

	// foreach episode see if is equal to the selected season
	seasonSelected = $( "#selectSeason" ).val() ;
	
	output="" ;
	count = 0 ;
	$.each( episodes, function( key, episode ) {
		if( episode.season == seasonSelected || seasonSelected == 0 ){
			if(count%2 == 0){
				output += '<div class="row">' ;
			}
        
	output += `<div class='col-sm-6 episode'>
    <div class='media'>
        <div class='media-left'>
            <img src='${episode.image}' class='media-object' alt='${episode.title}' width='200' data-toggle='popover' data-content='${episode.title}'>
        </div>
        <div class='media-body'>
            <h4 class='media-heading'>${episode.title}<small><i>Season ${episode.season} Episode ${episode.episode}</i></small></h4>
            <p>${episode.synopsis}</p>
        </div>
    </div>
</div>` ;

   		if(count%2 == 1){
				output += '</div>' ;
			}
			count++ ;
	}
		
	
	} );

	// Clear popovers
	$('.media-object').popover('hide'); 

	$('#episodes').html(output) ;
	
	// Recall the javascript to sort popovers
	$('.media-object').click(function(){
    $('.media-object').not(this).popover('hide'); //all but this
	});
});
