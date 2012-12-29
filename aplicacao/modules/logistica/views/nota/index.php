<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="STYLESHEET" href="<?php echo base_url('web/css/commom/') ?>/print.css" type="text/css" />
</head>

<body>

<div id="body">

<div id="section_header">
</div>

<div id="content">
  
<div class="page" style="font-size: 7pt">
<table style="width: 100%;" class="header">
<tr>
<td><h1 style="text-align: left"><?php  echo $nota[0]->cliente;?></h1></td>
<td><h1 style="text-align: right">Nota: <?php  echo $nota[0]->nota;?></h1></td>
</tr>
</table>

<table style="width: 100%; font-size: 8pt;">
<tr>
<td>Nota: <strong><?php  echo $nota[0]->nota;?></strong></td>
<td></td>
</tr>

<tr>
<td>Data: <strong><?php echo format_data_br($nota['0']->data_nota); ?></strong></td>
<td></strong></td>
</tr>

<tr>
<td>Endere&ccedil;o: <strong><?php echo $nota[0]->tipo_logradouro;?> <?php echo $nota[0]->logradouro;?></strong></td>
<td>Legal: <strong>N/A</strong></td>
</tr>
</table>


<table class="change_order_items">

<tr><td colspan="6"><h2>Dados do Produto</h2></td></tr>

<tbody>
<tr>
<th>C&oacute;digo <br />Produto</th>
<th>Descri&ccedil;&atilde;o</th>
<th>Quantidade</th>
<th colspan="2">Unidade</th>
<th>Total</th>
</tr>
<?php
$i=0;
$tb='';
$total = 0;
foreach($nota as $nf){
    $class = ($i%2==0?'even_row':'odd_row');
   $tb.= '<tr class="'.$class.'">
<td style="text-align: center">'.$nf->uid.'</td>
<td>'.$nf->produto.'</td>
<td style="text-align: center">'.$nf->qtd.'</td>
<td style="text-align: right; border-right-style: none;">'.format_moeda($nf->price).'</td>
<td class="change_order_unit_col" style="border-left-style: none;"></td>
<td class="change_order_total_col">'.format_moeda($nf->subtotal).'</td>
</tr>';
$total += $nf->subtotal;
$i++;
}
echo $tb;


?>

</tbody>




<tr>
<td colspan="3" style="text-align: right;"></td>
<td colspan="2" style="text-align: right;"><strong>TOTAL:</strong></td>
<td class="change_order_total_col"><strong><?php  echo format_moeda($total);?></strong></td></tr>
</table>

<table class="sa_signature_box" style="border-top: 1px solid black; padding-top: 2em;">

  <tr>    
    <td>Assinatura</td><td class="written_field" style="padding-left: 2.5in">&nbsp;</td>
    <td style="padding-left: 1em"></td><td class="written_field" style="padding-left: 2.5in; text-align: right;"></td>
  </tr>
  

</table>

</div>

</div>
</div>

<script type="text/php">

if ( isset($pdf) ) {

  $font = Font_Metrics::get_font("verdana");
  // If verdana isn't available, we'll use sans-serif.
  if (!isset($font)) { Font_Metrics::get_font("sans-serif"); }
  $size = 6;
  $color = array(0,0,0);
  $text_height = Font_Metrics::get_font_height($font, $size);

  $foot = $pdf->open_object();
  
  $w = $pdf->get_width();
  $h = $pdf->get_height();

  // Draw a line along the bottom
  $y = $h - 2 * $text_height - 24;
  $pdf->line(16, $y, $w - 16, $y, $color, 1);

  $y += $text_height;

  $text = "Job: 132-003";
  $pdf->text(16, $y, $text, $font, $size, $color);

  $pdf->close_object();
  $pdf->add_object($foot, "all");

  global $initials;
  $initials = $pdf->open_object();
  
  // Add an initals box
  $text = "Initials:";
  $width = Font_Metrics::get_text_width($text, $font, $size);
  $pdf->text($w - 16 - $width - 38, $y, $text, $font, $size, $color);
  $pdf->rectangle($w - 16 - 36, $y - 2, 36, $text_height + 4, array(0.5,0.5,0.5), 0.5);
    

  $pdf->close_object();
  $pdf->add_object($initials);
 
  // Mark the document as a duplicate
  $pdf->text(110, $h - 240, "DUPLICATE", Font_Metrics::get_font("verdana", "bold"),
             110, array(0.85, 0.85, 0.85), 0, 0, -52);

  $text = "Page {PAGE_NUM} of {PAGE_COUNT}";  

  // Center the text
  $width = Font_Metrics::get_text_width("Page 1 of 2", $font, $size);
  $pdf->page_text($w / 2 - $width / 2, $y, $text, $font, $size, $color);
  
}
</script>

</body>
</html>