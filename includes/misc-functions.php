<?php
/*displays the excerpt of the current post with a max character length specified*/
function sf_answer_excerpt($charlength) {
   $excerpt = get_the_excerpt();
   $charlength++;
   if(strlen($excerpt)>$charlength) {
       $subex = substr($excerpt,0,$charlength-5);
       $exwords = explode(" ",$subex);
       $excut = -(strlen($exwords[count($exwords)-1]));
       if($excut<0) {
            echo substr($subex,0,$excut);
       } else {
       	    echo $subex;
       }
       echo "[...]";
   } else {
	   echo $excerpt;
   }
}

function sf_make_protocol_relative_url( $url ) {
    return preg_replace( '(https?://)', '//', $url );
}
