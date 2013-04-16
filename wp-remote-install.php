<?php

// Global Configuration
set_time_limit( 0 );

// Suggested Plugins and Themes
$suggestions = array(

  'plugins' => array(

    # Remote Administration
    'http://downloads.wordpress.org/plugin/wpremote.zip' ,

    # Migration Tool
    'http://downloads.wordpress.org/plugin/duplicator.zip' ,

    # Login Security
    'http://downloads.wordpress.org/plugin/login-security-solution.zip' ,

    # Google Analytics
    'http://downloads.wordpress.org/plugin/google-analyticator.zip' ,
    # Redirect
    'http://downloads.wordpress.org/plugin/page-links-to.zip' ,
    # Cache
    'http://downloads.wordpress.org/plugin/wp-super-cache.1.3.zip' ,
    'http://downloads.wordpress.org/plugin/jetpack.2.2.2.zip' ,
    # Theme Test
    'http://downloads.wordpress.org/plugin/theme-check.20121211.1.zip' ,
    # SEO
    'http://downloads.wordpress.org/plugin/wordpress-seo.1.4.6.zip' ,
    # SEO Google Sitemap Generator
    'http://downloads.wordpress.org/plugin/google-sitemap-generator.3.2.9.zip' ,
    # Facebook Integration
    'http://downloads.wordpress.org/plugin/facebook.1.3.1.zip'

  ) ,

  'themes' => array(

    'http://wordpress.org/extend/themes/download/responsive.1.9.3.zip' ,
    'http://wordpress.org/extend/themes/download/spun.1.06.zip' ,
    'http://wordpress.org/extend/themes/download/pagelines.1.3.9.zip' ,
    'http://wordpress.org/extend/themes/download/ifeature.5.1.10.zip' ,
    'http://wordpress.org/extend/themes/download/eclipse.2.0.3.zip'

  )

);

// Function for Extraction
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
  die( 'Unable to open '.$zipFile );
  return false;
}

// Declare Parameters
$step = 1;
if( isset( $_POST['step'] ) )
  $step = (int) $_POST['step'];

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="chrome=1" />
<meta name="description" content="WordPress Remote Installer - Remote installation of WordPress, Plugins and Themes with only one file being uploaded via FTP" />
<link rel="stylesheet" type="text/css" media="screen" href="http://lucanos.github.io/WordPress-Remote-Installer/stylesheets/stylesheet.css" />
<title>WordPress Remote Installer</title>
<style>
form { text-align:right }
textarea { width:100%;min-height:200px }
</style>
</head>
<body>

<!-- HEADER -->
<div id="header_wrap" class="outer">
  <header class="inner">
    <a id="forkme_banner" href="https://github.com/lucanos/WordPress-Remote-Installer">View on GitHub</a>
    <h1 id="project_title">WordPress Remote Installer</h1>
    <h2 id="project_tagline">Remote installation of WordPress, Plugins and Themes with only one file being uploaded via FTP</h2>
  </header>
</div>

<!-- MAIN CONTENT -->
<div id="main_content_wrap" class="outer">
  <section id="main_content" class="inner">

    <h1>WordPress Remote Installer</h1>
<?php

switch( $step ){

  default :
  case 1 :

    $tests = array(
      array(
        'result' => ini_get( 'allow_url_fopen' ) ,
        'pass' => '<strong>allow_url_open</strong> is Enabled' ,
        'fail' => '<strong>allow_url_open</strong> is Disabled'
      ) ,
      array(
        'result' => ( glob( '*' ) == array( 'wp-remote-install.php' ) ) ,
        'pass' => 'The server is empty (apart from this file)' ,
        'fail' => 'The server is not empty.'
      )
    )
?>
    <h2>Pre-Install Checks</h2>
    <ul>
<?php
    $proceed = true;
    foreach( $tests as $t ){
      if( !$t['result'] )
        $proceed = false;
?>
      <li class="<?php echo ( $t['result'] ? 'pass' : 'fail' ); ?>"><?php echo $t[( $t['result'] ? 'pass' : 'fail' )]; ?></li>
<?php
    }
?>
    </ul>
<?php
    if( !$proceed ){
?>
    <p>NOTE: We are unable to proceed until the above issue(s) are resolved.</p>
<?php
    }else{
?>
    <form method="post">
      <input type="hidden" name="step" value="2" />
      <input type="submit" name="submit" value="Commence Install of WordPress"/>
    </form>
<?php
    }
    break;

  case 2 :
?>
    <h2>Installing WordPress</h2>
    <ul>
      <li>Downloading Latest WordPress from Wordpress.org - <?php echo ( ( $wordpressInstaller = @file_get_contents( 'https://wordpress.org/latest.zip' ) ) ? 'OK' : 'FAILED' ); ?></li>
      <li>Save Latest WordPress Locally - <?php echo ( file_put_contents( 'wordpress.zip' , $wordpressInstaller ) ? 'OK' : 'FAILED' ); ?></li>
      <li>Extract WordPress - <?php echo ( extractSubFolder( 'wordpress.zip' , null , 'wordpress' ) ? 'OK' : 'FAILED' ); ?></li>
      <li>Delete WordPress ZIP - <?php echo ( unlink( 'wordpress.zip' ) ? 'OK' : 'FAILED' ); ?></li>
    </ul>
    <form method="post">
      <input type="hidden" name="step" value="3" />
      <input type="submit" name="submit" value="Select Plugins" />
    </form>
<?php
    break;

  case 3 :
?>
    <h2>Installing WordPress Plugins</h2>
    <p>List the Download URLs for all WordPress Plugins, one per line</p>
    <form method="post">
      <textarea name="plugins"><?php echo implode( "\n" , $suggestions['plugins'] ); ?></textarea>
      <input type="hidden" name="step" value="4" />
      <input type="submit" name="submit" value="Install Plugins" />
    </form>
<?php
    break;

  case 4 :
?>
    <h2>Installing WordPress Plugins</h2>
    <ul>
      <li>Delete Unneeded "Hello Dolly" Plugin - <?php echo ( ( !file_exists( @unlink( dirname( __FILE__ ).'/wp-content/plugins/hello.php' ) || dirname( __FILE__ ).'/wp-content/plugins/hello.php' ) ) ? 'OK' : 'FAILED' ); ?></li>
<?php

if( isset( $_POST['plugins'] ) ){
  $plugins = explode( "\n" , $_POST['plugins'] );
  foreach( $plugins as $url ){
    $url = trim( $url );
    if( strpos( $url , 'http' )!==0 )
      $url = 'http://'.$url;
    if( preg_match( '/^(http?\:\/\/?downloads\.wordpress\.org\/plugin\/)([^\.]+)((?:\.\d+)+)?\.zip$/' , $url , $bits ) )
      $url = $bits[1].$bits[2].'.zip';
?>
      <li>Installing <strong><?php echo $bits[2]; ?></strong> -
<?php
    $get = @file_get_contents( $url );
    if( !$get ){
      echo 'FAILED TO DOWNLOAD';
    }else{
      file_put_contents( 'temp_plugin.zip' , $get );
      if( !extractSubFolder( 'temp_plugin.zip' , dirname( __FILE__ ).'/wp-content/plugins' ) ){
        echo 'FAILED TO EXTRACT';
      }else{
        echo 'OK';
      }
      @unlink( 'temp_plugin.zip' );
    }
    echo '</li>';
  }
}

?>
    </ul>
    <form method="post">
      <input type="hidden" name="step" value="5" />
      <input type="submit" name="submit" value="Select Themes" />
    </form>
<?php
    break;

  case 5 :
?>
    <h2>Installing WordPress Themes</h2>
    <p>List the Download URLs for all WordPress Themes, one per line</p>
    <form method="post">
      <textarea name="themes"><?php echo implode( "\n" , $suggestions['themes'] ); ?></textarea>
      <input type="hidden" name="step" value="6" />
      <input type="submit" name="submit" value="Install Themes" />
    </form>
<?php
    break;

  case 6 :
?>
    <h2>Installing WordPress Themes</h2>
    <ul>
<?php

if( isset( $_POST['themes'] ) ){
  $plugins = explode( "\n" , $_POST['themes'] );
  foreach( $plugins as $url ){
    $url = trim( $url );
    if( !$url ) continue;
    if( strpos( $url , 'http' )!==0 )
      $url = 'http://'.$url;
    preg_match( '/^(http?\:\/\/?wordpress.org\/extend\/themes\/download\/)([^\.]+)((?:\.\d+)+)\.zip$/' , $url , $bits );
?>
      <li>Installing <strong><?php echo $bits[2]; ?>.zip</strong> -
<?php
    $get = @file_get_contents( $url );
    if( !$get ){
      echo 'FAILED TO DOWNLOAD';
    }else{
      file_put_contents( 'temp_theme.zip' , $get );
      if( !extractSubFolder( 'temp_theme.zip' , dirname( __FILE__ ).'/wp-content/themes' ) ){
        echo 'FAILED TO EXTRACT';
      }else{
        echo 'OK';
      }
      @unlink( 'temp_theme.zip' );
    }
    echo '</li>';
  }
}

?>
    </ul>
    <form method="post">
      <input type="hidden" name="step" value="7" />
      <input type="submit" name="submit" value="Clean Up" />
    </form>
<?php
    break;

  case 7 :
?>
    <h2>Cleaning Up</h2>
    <ul>
      <li>Remove WordPress Installer - <?php echo ( !file_exists( 'wordpress.zip' ) || @unlink( 'wordpress.zip' ) ? 'OK' : 'FAILED' ); ?></li>
      <li>Remove Temporary Plugin File - <?php echo ( !file_exists( 'temp_plugin.zip' ) || @unlink( 'temp_plugin.zip' ) ? 'OK' : 'FAILED' ); ?></li>
      <li>Remove Temporary Theme File - <?php echo ( !file_exists( 'temp_theme.zip' ) || @unlink( 'temp_theme.zip' ) ? 'OK' : 'FAILED' ); ?></li>
      <li>Remove WordPress Remote Installer - <?php echo ( !file_exists( __FILE__ ) || @unlink( __FILE__ ) ? 'OK' : 'FAILED' ); ?></li>
    </ul>
    <form method="post" action="./wp-admin/setup-config.php">
      <input type="submit" name="submit" value="Launch WordPress Installer" />
    </form>
<?php
    break;

}

?>
  </section>
</div>

<!-- FOOTER  -->
<div id="footer_wrap" class="outer">
  <footer class="inner">
    <p class="copyright">WordPress Remote Installer maintained by <a href="https://github.com/lucanos">lucanos</a></p>
    <p>Published with <a href="http://pages.github.com">GitHub Pages</a></p>
  </footer>
</div>

</body>
</html>
