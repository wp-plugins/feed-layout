<?php
function fdl_request($name, $default=null)
{
    if (!isset($_REQUEST[$name])) return $default;
    if (get_magic_quotes_gpc()) return fdl_stripslashes($_REQUEST[$name]);
    else return $_REQUEST[$name];
}

function fdl_stripslashes($value)
{
    $value = is_array($value) ? array_map('fdl_stripslashes', $value) : stripslashes($value);
    return $value;
}

function fdl_field_text($name, $label='', $tips='', $attrs='')
{
  global $options;
  if (strpos($attrs, 'size') === false) $attrs .= 'size="30"';
  echo '<tr valign="top"><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="text" ' . $attrs . ' name="options[' . $name . ']" value="' .
    htmlspecialchars($options[$name]) . '"/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

function fdl_field_checkbox($name, $label='', $tips='', $attrs='')
{
  global $options;
  echo '<tr valign="top"><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' .
    ($options[$name]!= null?'checked':'') . '/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

function fdl_field_textarea($name, $label='', $tips='', $attrs='')
{
  global $options;

  if (strpos($attrs, 'cols') === false) $attrs .= 'cols="70"';
  if (strpos($attrs, 'rows') === false) $attrs .= 'rows="5"';

  echo '<tr valign="top"><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><textarea wrap="off" ' . $attrs . ' name="options[' . $name . ']">' .
    htmlspecialchars($options[$name]) . '</textarea>';
  echo '<br /> ' . $tips;
  echo '</td></tr>';
}

$options = get_option('fdl');

if (isset($_POST['save']))
{
    if (!check_admin_referer()) die('No hacking please');
    $options = stripslashes_deep($_POST['options']);
    update_option('fdl', $options);
}
?>
<div class="wrap">

<h2>Feed Layout</h2>

<p>To have more information about Feed Layout read the <a href="http://www.satollo.com/english/wordpress/feed-layout">official plugin page</a>
or write me to info@satollo.com.</p>

<p>Consider a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2545483">DONATION</a> to support this plugin.</p>

<p>
My plugins: 
<a href="http://www.satollo.com/english/wordpress/post-layout">Post Layout</a>, 
<a href="http://www.satollo.com/english/wordpress/feed-layout">Feed Layout</a>,
<a href="http://www.satollo.com/english/wordpress/hyper-cache">Hyper Cache</a>.
</p>

<form method="post" action="">
<?php wp_nonce_field(); ?>
<table class="form-table">
<?php fdl_field_checkbox('break', 'Break feed items if thay have a "read more" break'); ?>
<?php fdl_field_textarea('before', 'Code before the post'); ?>
<?php fdl_field_textarea('more', 'Code after the more break'); ?>
<?php fdl_field_textarea('after', 'Code after the post'); ?>
</table>

<p class="submit"><input type="submit" name="save" value="Save"/></p>
</form>
</div>
