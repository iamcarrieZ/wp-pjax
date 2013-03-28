<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

define('WP_DEBUG', true);
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED );
ini_set("display_errors", 1);



if (empty($this->_config['pe-wp-pjax-menu-selector']) || empty($this->_config['pe-wp-pjax-content-selector']) )
{
    trigger_error('ERROR: WP-PJAX Not set up correctly. Not loading JS. '.__FILE__ . ' (' .__LINE__ . ')', E_USER_NOTICE );
    return '';
}

if( !empty( $this->_config['pe-wp-pjax-menu-active-class'] ) )
{
    $active_classes = explode(' ',  $this->_config['pe-wp-pjax-menu-active-class']);

    $active_classes = '.'.implode(', .', $active_classes);
    //echo $active_classes;
    /*
    $active_class_array = array();

    foreach($active_classes AS $ac) 
    {
        $active_class_array[] = trim($ac, ',. ');
    }
    */
}

?>

<script type = "text/javascript" charset = "UTF-8">

jQuery.cookie=function(a,b,c){if(arguments.length>1&&String(b)!=="[object Object]"){c=jQuery.extend({},c);if(b===null||b===undefined)c.expires=-1;if(typeof c.expires=="number"){var d=c.expires,e=c.expires=new Date;e.setDate(e.getDate()+d)}b=String(b);return document.cookie=[encodeURIComponent(a),"=",c.raw?b:encodeURIComponent(b),c.expires?"; expires="+c.expires.toUTCString():"",c.path?"; path="+c.path:"",c.domain?"; domain="+c.domain:"",c.secure?"; secure":""].join("")}c=b||{};var f,g=c.raw?function(a){return a}:decodeURIComponent;return(f=(new RegExp("(?:^|; )"+encodeURIComponent(a)+"=([^;]*)")).exec(document.cookie))?g(f[1]):null}

var time;
var localtorage;

(function($){
    
    $(document).pjax('<?php echo $this->_config['pe-wp-pjax-menu-selector']; ?>',  '<?php echo $this->_config['pe-wp-pjax-content-selector']; ?>');//, {
        //fragment: '<?php echo $this->_config['pe-wp-pjax-content-selector']; ?>',
        //timeout: 4000
    //))});
    /*
    if ($.support.pjax) {
        $(document).on('click', '<?php echo $this->_config['pe-wp-pjax-menu-selector']; ?>', function(event) {
            //var container = $(this).closest('[data-pjax-container]')
            $.pjax.click(event, {container: '<?php echo $this->_config['pe-wp-pjax-content-selector']; ?>'})
        })
    }
    */
        
<?php if(!empty($this->_config['pe-wp-pjax-load-timeout'])) : ?>
    $.pjax.defaults.timeout = <?php echo $this->_config['pe-wp-pjax-load-timeout']; ?>;
<?php elseif( $this->_config['pe-wp-pjax-load-timeout'] == '0' ) : ?> 
    $.pjax.defaults.timeout = false;
<?php endif; ?>
    
    
    $(document).on('pjax:timeout', function(event) {
      // Prevent default timeout redirection behavior
      event.preventDefault()
    })
   
    $(document).on('pjax:timeout', function(event) {
        // Prevent default timeout redirection behavior
        event.preventDefault();
        alert('timeout');
    });
    
    $(document).on('pjax:beforeSend', function(event, request, settings) {
        
		//deleteAllCookies();
		
        console.log(event);
        
        //console.log(request );
        //console.log(settings.url);
        
   
        //Hack to get a location object from url string
        url = document.createElement('a');
        url.href = settings.url;
        
        var protocol = url.protocol;
        var hash = url.hash;
        
        //console.log(url.pathname);
        
        //Remove old link active classes
        $('<?php echo $active_classes; ?>').removeClass('<?php echo $this->_config['pe-wp-pjax-menu-active-class']; ?>');
        //$('a').parent().removeClass('<?php echo $this->_config['pe-wp-pjax-menu-active-class']; ?>');
        //add link active classes
        $("a[href$='"+url.pathname+"']").parent().addClass('<?php echo $this->_config['pe-wp-pjax-menu-active-class']; ?>');//.css('background-color','red');
        
        
		//return false;
    });

    $(document).on('pjax:start', function(xhr) {
    
        //Prevent double loading and load chaining
        if (xhr) 
        {
            //xhr.abort();
            //return false;
        }

/*

        localtorage =false;
        
        localtorage = sessionStorage.getItem('yourkey');
        */


        <?php if(  $this->_config['pe-wp-pjax-show-extended-notice'] ) : ?>
        var d = new Date();
        time = d.getTime()
        <?php endif; ?>
            
        <?php if( $this->_config['pe-wp-pjax-content-fade'] ) : ?>
            
            $('<?php echo $this->_config['pe-wp-pjax-content-selector']; ?>').animate({opacity: 0.1}, <?php echo $this->_config['pe-wp-pjax-content-fade-timeout-out']; ?>);
                
        <?php endif; ?>
    });

      $(document).on('pjax:end', function(event, request, settings) {
        
        console.log(event);
        console.log(request);
        console.log(settings);
        
        console.log(request.responseText);
          /*
        */
        //sessionStorage.setItem(settings.url, request.responseText)

        <?php if( $this->_config['pe-wp-pjax-content-fade'] ) : ?>
            
            $('<?php echo $this->_config['pe-wp-pjax-content-selector']; ?>').animate({opacity: 1}, <?php echo $this->_config['pe-wp-pjax-content-fade-timeout-in']; ?>);
                //fadeIn(<?php echo $this->_config['pe-wp-pjax-content-fade-timeout-in']; ?>)
            
        <?php endif; ?>
          /*
        console.log(event);
            console.log(request);
        
            console.log(settings);
        */
		
		
		
       /*
            var page_id = request.getResponseHeader('PJAX-post-id');
            
            $('<?php echo $active_classes; ?>').removeClass('<?php echo $this->_config['pe-wp-pjax-menu-active-class']; ?>');
            $('.page-item-'+page_id).addClass('<?php echo $this->_config['pe-wp-pjax-menu-active-class']; ?>'); //.parent().parent('li').addClass('<?php echo $this->_config['pe-wp-pjax-menu-active-class']; ?>');
       */
       
            /*  
        console.log(event);
        console.log(request);
        console.log(request.readyState);
        console.log(request.responseText);
        console.log(request);
        */
             //console.log(request.responseText);
             //console.log(request);
             
            console.log(request.responseText);
        
            <?php if( $this->_config['pe-wp-pjax-show-notice'] == 1 ) : ?>
            
            var noticeText;
            <?php if(  $this->_config['pe-wp-pjax-show-extended-notice'] ) : ?>
            var cacheHit = request.getResponseHeader('PJAX-Page-Cache');
            var XCacheHit = request.getResponseHeader('X-Cache-Hit');
            var resource = request.getResponseHeader('PJAX-loaded-resource');

            var d = new Date();
            time = d.getTime() - time;
            
            noticeText = resource + '<br /> Loaded with PJAX! <br /> Load time: ' + time + 'ms (total front end) <br /> PJAX page cache: ' + cacheHit + '<br /> Varnish cache: ' + XCacheHit;
            
			<?php else : ?>
            
            noticeText = 'Loaded with PJAX!';
            
            <?php endif; ?>
                
            $.noticeAdd({
                text: noticeText,
                <?php if( $this->_config['pe-wp-pjax-notice-sticky'] ) : ?>
                stay: true
                <?php else : ?>
                stayTime: <?php echo $this->_config['pe-wp-pjax-notice-timeout']; ?>
                <?php endif; ?>
            });
            
            <?php endif; ?>
        });


     
    
    $(document).ready(function() {

        <?php if( $this->_config['pe-wp-pjax-show-notice'] == 1 ) : ?>

            
        if( !$.support.pjax ) {
            $('#wp-pjax-toggle-container').html('<p style="color: red">PJAX is not supported in this browser. Se <a href="http://caniuse.com/#search=pushstate">compatibility list</a></p>');
        }
        
        $('.notice-item').on('click', function() {
            alert('asdasd');
            jQuery.noticeRemove($(this), 400);
        });

        $('#wp-pjax-toggle').change( function() {
            
            if( $(this).prop('checked') )
            {
                $('#wp-pjax-toggle-status').html('Enabled').css('color', 'green');
            }
            else
            {
                $(document).pjax.disable();
                alert('disable');
                $('#wp-pjax-toggle-status').html('Disabled').css('color', 'red');
            }
            
        });
        <?php endif; ?>
    });
    
    function getLocation(href) {
        var l = document.createElement("a")
        l.href = href
        return l
    }
    
})(jQuery);

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
    	var cookie = cookies[i];
    	var eqPos = cookie.indexOf("=");
    	var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
    	document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}


</script>

<?

?>