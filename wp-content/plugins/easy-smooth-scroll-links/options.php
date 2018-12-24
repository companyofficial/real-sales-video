<div class="wrap">
  <h2><?php _e('Easy Smooth Scroll Links Setting', 'easy-smooth-scroll-links' ); ?></h2>

  <ul> 	
    <li>
      <a href="https://www.pandasilk.com/wordpress-easy-smooth-scroll-links-plugin/" target="_blank" rel="noopener"><?php _e('Documents and Troubleshooting', 'easy-smooth-scroll-links' ); ?>
      </a>
    </li> 	
    <li>
      <a href="https://wordpress.org/support/plugin/easy-smooth-scroll-links" target="_blank" rel="noopener"><?php _e('Support Forum on WordPress.org', 'easy-smooth-scroll-links' ); ?>
      </a>
    </li>
  </ul>    
  <?php screen_icon(); ?> 	
  <form action="options.php" method="post" id="<?php echo $plugin_id; ?>_options_form" name="<?php echo $plugin_id; ?>_options_form">	
    <?php settings_fields($plugin_id.'_options'); ?>    
    <table>		
      <tbody>		   
        <tr>			 
          <td style="padding:5px;font-family:Verdana, Geneva, sans-serif;color:#666;">                 
            <label for="essl_speed">                     
              <p><?php _e('Scroll Speed ( smaller number, faster, default is 900 )', 'easy-smooth-scroll-links' ); ?>
              </p>                     
              <p>
                <input  size="10" id="speed" type="text" name="essl_speed" value="<?php echo get_option('essl_speed'); ?>" />
              </p>                 
            </label>             
          </td>		   
        </tr>		   
        <tr>			 
          <td style="padding:5px;font-family:Verdana, Geneva, sans-serif;color:#666;">                 
            <label for="essl_offset">                     
              <p><?php _e('Offset ( default is 20 )', 'easy-smooth-scroll-links' ); ?>
              </p>                     
              <p>
                <input  size="10"  id="offset" type="text" name="essl_offset" value="<?php echo get_option('essl_offset'); ?>" />
              </p>                 
            </label>             
          </td>		   
        </tr>		   
        <tr>			 
          <td style="padding:5px;font-family:Verdana, Geneva, sans-serif;color:#666;">                 
            <label for="essl_easing">                     
              <p><?php _e('Scrolling Animation Effects ( default is easeInQuint ) ', 'easy-smooth-scroll-links' ); ?>
              </p>                     
              <p>
                <select name="essl_easing">    
                  <option disabled="disabled" ><?php _e('Choose an Animation Effects', 'easy-smooth-scroll-links' ); ?>
                  </option>    
                  <option name="essl_easing" value="easeInBounce" 
                          <?php echo (get_option('essl_easing') == "easeInBounce") ? 'selected="selected"' : ''; ?>>easeInBounce
                  </option>    
                <option name="essl_easing" value="easeOutBounce"  
                        <?php echo (get_option('essl_easing') == "easeOutBounce") ? 'selected="selected"' : ''; ?>>easeOutBounce
              </option>    
            <option name="essl_easing" value="easeInOutBounce"  
                    <?php echo (get_option('essl_easing') == "easeInOutBounce") ? 'selected="selected"' : ''; ?>>easeInOutBounce
      </option>    
        <option name="essl_easing" value="easeInElastic"  
                <?php echo (get_option('essl_easing') == "easeInElastic") ? 'selected="selected"' : ''; ?>>easeInElastic
      </option>    
    <option name="essl_easing" value="easeOutElastic"  
            <?php echo (get_option('essl_easing') == "easeOutElastic") ? 'selected="selected"' : ''; ?>>easeOutElastic
    </option>    
  <option name="essl_easing" value="easeInOutElastic"  
          <?php echo (get_option('essl_easing') == "easeInOutElastic") ? 'selected="selected"' : ''; ?>>easeInOutElastic
  </option>    
<option name="essl_easing" value="easeInCirc"  
        <?php echo (get_option('essl_easing') == "easeInCirc") ? 'selected="selected"' : ''; ?>>easeInCirc
</option>    
<option name="essl_easing" value="easeOutCirc"  
        <?php echo (get_option('essl_easing') == "easeOutCirc") ? 'selected="selected"' : ''; ?>>easeOutCirc
</option>    
<option name="essl_easing" value="easeInOutCirc"  
        <?php echo (get_option('essl_easing') == "easeInOutCirc") ? 'selected="selected"' : ''; ?>>easeInOutCirc
</option>    
<option name="essl_easing" value="easeInBack"  
        <?php echo (get_option('essl_easing') == "easeInBack") ? 'selected="selected"' : ''; ?>>easeInBack
</option>    
<option name="essl_easing" value="easeOutBack"  
        <?php echo (get_option('essl_easing') == "easeOutBack") ? 'selected="selected"' : ''; ?>>easeOutBack
</option>    
<option name="essl_easing" value="easeInOutBack"  
        <?php echo (get_option('essl_easing') == "easeInOutBack") ? 'selected="selected"' : ''; ?>>easeInOutBack
</option>    
<option name="essl_easing" value="easeInQuint"  
        <?php echo (get_option('essl_easing') == "easeInQuint") ? 'selected="selected"' : ''; ?>>easeInQuint
</option>    
<option name="essl_easing" value="easeOutQuint"  
        <?php echo (get_option('essl_easing') == "easeOutQuint") ? 'selected="selected"' : ''; ?>>easeOutQuint
</option>    
<option name="essl_easing" value="easeInOutQuint"  
        <?php echo (get_option('essl_easing') == "easeInOutQuint") ? 'selected="selected"' : ''; ?>>easeInOutQuint
</option>    
<option name="essl_easing" value="easeInExpo"  
        <?php echo (get_option('essl_easing') == "easeInExpo") ? 'selected="selected"' : ''; ?>>easeInExpo
</option>    
<option name="essl_easing" value="easeOutExpo"  
        <?php echo (get_option('essl_easing') == "easeOutExpo") ? 'selected="selected"' : ''; ?>>easeOutExpo
</option>    
<option name="essl_easing" value="easeInOutExpo"  
        <?php echo (get_option('essl_easing') == "easeInOutExpo") ? 'selected="selected"' : ''; ?>>easeInOutExpo
</option>    
<option name="essl_easing" value="easeInCubic"  
        <?php echo (get_option('essl_easing') == "easeInCubic") ? 'selected="selected"' : ''; ?>>easeInCubic
</option>    
<option name="essl_easing" value="easeOutCubic"  
        <?php echo (get_option('essl_easing') == "easeOutCubic") ? 'selected="selected"' : ''; ?>>easeOutCubic
</option>    
<option name="essl_easing" value="easeInOutCubic"  
        <?php echo (get_option('essl_easing') == "easeInOutCubic") ? 'selected="selected"' : ''; ?>>easeInOutCubic
</option>    
<option name="essl_easing" value="easeInQuart"  
        <?php echo (get_option('essl_easing') == "easeInQuart") ? 'selected="selected"' : ''; ?>>easeInQuart
</option>    
<option name="essl_easing" value="easeOutQuart"  
        <?php echo (get_option('essl_easing') == "easeOutQuart") ? 'selected="selected"' : ''; ?>>easeOutQuart
</option>    
<option name="essl_easing" value="easeInOutQuart"  
        <?php echo (get_option('essl_easing') == "easeInOutQuart") ? 'selected="selected"' : ''; ?>>easeInOutQuart
</option>    
<option name="essl_easing" value="easeInSine"  
        <?php echo (get_option('essl_easing') == "easeInSine") ? 'selected="selected"' : ''; ?>>easeInSine
</option>    
<option name="essl_easing" value="easeOutSine"  
        <?php echo (get_option('essl_easing') == "easeOutSine") ? 'selected="selected"' : ''; ?>>easeOutSine
</option>    
<option name="essl_easing" value="easeInOutSine"  
        <?php echo (get_option('essl_easing') == "easeInOutSine") ? 'selected="selected"' : ''; ?>>easeInOutSine
</option>    
<option name="essl_easing" value="easeInQuad"  
        <?php echo (get_option('essl_easing') == "easeInQuad") ? 'selected="selected"' : ''; ?>>easeInQuad
</option>    
<option name="essl_easing" value="easeOutQuad"  
        <?php echo (get_option('essl_easing') == "easeOutQuad") ? 'selected="selected"' : ''; ?>>easeOutQuad
</option>    
<option name="essl_easing" value="easeInOutQuad"  
        <?php echo (get_option('essl_easing') == "easeInOutQuad") ? 'selected="selected"' : ''; ?>>easeInOutQuad
</option>
</select>					 
</p>                 
</label>             
</td>		   
</tr>	
<tr>  	
  <td style="padding:5px;font-family:Verdana, Geneva, sans-serif;color:#666;">            			
    <label for="essl_exclude_begin">
      <p><?php _e('Exclude Anchors Beginning With ( default is #tab   #quicktab  #pane )	', 'easy-smooth-scroll-links' ); ?>			
      </p> 				
      <input id="essl_exclude_begin_1" type="text" name="essl_exclude_begin_1"  placeholder="#beginwith1" value="<?php  echo get_option('essl_exclude_begin_1'); ?>" /> 				
      <input id="essl_exclude_begin_2" type="text" name="essl_exclude_begin_2"  placeholder="#beginwith2" value="<?php  echo get_option('essl_exclude_begin_2'); ?>" /> 				
      <input id="essl_exclude_begin_3" type="text" name="essl_exclude_begin_3"  placeholder="#beginwith3" value="<?php  echo get_option('essl_exclude_begin_3'); ?>" /> 				
      <input id="essl_exclude_begin_4" type="text" name="essl_exclude_begin_4"  placeholder="#beginwith4" value="<?php  echo get_option('essl_exclude_begin_4'); ?>" /> 				
      <input id="essl_exclude_begin_5" type="text" name="essl_exclude_begin_5"  placeholder="#beginwith5" value="<?php  echo get_option('essl_exclude_begin_5'); ?>" />				
      </p>                 			
</label>             	
</td>    
</tr>        
<tr>  	
  <td style="padding:5px;font-family:Verdana, Geneva, sans-serif;color:#666;">                 		
    <label for="essl_offset">
      <p><?php _e('Exclude Anchors Exactly Match', 'easy-smooth-scroll-links' ); ?>
      </p>			
      <p>			
        <input id="offset" type="text" name="essl_exclude_match_1"  placeholder="#exactmatch1" value="<?php echo get_option('essl_exclude_match_1'); ?>" />   			
        <input id="offset" type="text" name="essl_exclude_match_2"  placeholder="#exactmatch2" value="<?php echo get_option('essl_exclude_match_2'); ?>" />   			
        <input id="offset" type="text" name="essl_exclude_match_3"  placeholder="#exactmatch3" value="<?php echo get_option('essl_exclude_match_3'); ?>" />   			
        <input id="offset" type="text" name="essl_exclude_match_4"  placeholder="#exactmatch4" value="<?php echo get_option('essl_exclude_match_4'); ?>" />   			
        <input id="offset" type="text" name="essl_exclude_match_5"  placeholder="#exactmatch5" value="<?php echo get_option('essl_exclude_match_5'); ?>" />			
      </p>		
    </label>	
  </td>
</tr>
<tr>	
  <td style="padding:25px;font-family:Verdana, Geneva, sans-serif;color:#666;">	 		
    <label>
      <strong><?php _e('Enable Scrolling from One Page to Another ?', 'easy-smooth-scroll-links' ); ?>
      </strong> 
    </label>
    <input type="checkbox" name="enable_essl_aggressive" value="1"
           <?php checked(1,get_option('enable_essl_aggressive'));?> />
    <br> 
    <strong><?php _e('Warning', 'easy-smooth-scroll-links' ); ?>
    </strong>:<?php _e(' This feature is experimental, might cause conflict with some slider plugins, or blank pages.', 'easy-smooth-scroll-links' ); ?>	
  </td>
</tr>		   		
</tbody>				
<tfoot>		   
  <tr>			 
    <th>
      <input type="submit" name="submit" value="Save Settings" class="button button-primary" onClick="return empty()"  />
    </th>		   
  </tr>		
</tfoot>	
</table>	
</form>		       
</div>
