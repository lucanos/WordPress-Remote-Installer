jQuery(document).ready(function($){

  $('input.confirm')
    .on('click',function(e){
      var $t = $(this) ,
          msg = 'Are you sure?';
      if( $t.data( 'msg' ) )
        msg = $t.data( 'msg' );
      if( !confirm( msg ) )
        e.preventDefault();
    });

});