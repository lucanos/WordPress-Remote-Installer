<?php

set_time_limit( 0 );

// 1 Download Wordpress
$wordpressInstaller = @file_get_contents( 'https://wordpress.org/latest.zip' );
if( !$wordpressInstaller )
  die( 'Unable to download Wordpress Installer' );
file_put_contents( 'wordpress.zip' , $wordpressInstaller );
unset( $wordpressInstaller );

// 2 Extract Wordpress

function extractSubFolder( $zipFile , $target = null , $subFolder = null ){
  if( is_null( $target ) )
    $target = dirname( __FILE__ );
  $zip = new ZipArchive;
  $res = $zip->open( $zipFile );
  if( $res === TRUE ){
    if( is_null( $subFolder ) ){
      $zip->extractTo( $target );
    }else{
      for( $i = 0 , $c = $zip->numFiles ; $i < $c ; $i++ ){
        $entry = $zip->getNameIndex( $i );
        //Use strpos() to check if the entry name contains the directory we want to extract
        if( $entry!=$subFolder.'/' && strpos( $entry , $subFolder.'/' )===0 ){
          $stripped = substr( $entry , 9 );
          if( substr( $entry , -1 )=='/' ){
           // Subdirectory
            $subdir = $target.'/'.substr( $stripped , 0 , -1 );
            if( !is_dir( $subdir ) )
              mkdir( $subdir );
          }else{
            $stream = $zip->getStream( $entry );
            $write = fopen( $target.'/'.$stripped , 'w' );
            while( $data = fread( $stream , 1024 ) ){
              fwrite( $write , $data );
            }
            fclose( $write );
            fclose( $stream );
          }
        }
      }
    }
    $zip->close();
    return true;
  }
  return false;
}

// Extract Wordpress to Root
if( extractSubFolder( 'wordpress.zip' , null , 'wordpress' ) ){

  // Delete the Wordpress Installer
  unlink( 'wordpress.zip' );
  
  // Get some Plugins
  $plugins = array(
    'The WP Remote WordPress Plugin' => 'http://downloads.wordpress.org/plugin/wpremote.zip' ,
    'Duplicator' => 'http://downloads.wordpress.org/plugin/duplicator.zip' ,
    'Better WP Security' => 'http://downloads.wordpress.org/plugin/better-wp-security.zip' ,
    'Page Links To' => 'http://downloads.wordpress.org/plugin/page-links-to.zip' ,
    'Google Analyticator' => 'http://downloads.wordpress.org/plugin/google-analyticator.zip'
  );
  foreach( $plugins as $title => $url ){
    $get = @file_get_contents( $url );
    if( !$get ){
      echo 'Failed to download '.$title;
    }else{
      file_put_contents( 'temp_plugin.zip' , $get );
      if( !extractSubFolder( 'temp_plugin.zip' , dirname( __FILE__ ).'/wp-content/plugins' ) ){
        echo 'Failed to extract '.$title;
      }else{
        //echo 'Installed '.$title;
      }
      unlink( 'temp_plugin.zip' );
    }
  }
  if( !headers_sent() ){
    header( 'Location: index.php' );
    die();
  }

}else{

  echo 'D\'oh!';

}

