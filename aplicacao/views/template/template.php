<?php 
//
if(isset($header_parse)){
  $this->parser->parse($header_parse,$data,false);	
}else{
  $this->load->view($header,$data);	
}
if(isset($content_parse)){
  $this->parser->parse($content_parse,$data,false);	
}else{
	
   $this->load->view($content,$data);	
}

if(isset($footer_parse)){
	
   $this->parser->parse($footer_parse,$data,false);	
}else{
   $this->load->view($footer,$data);	
}


