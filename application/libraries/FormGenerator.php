<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//input types: text, password, datetime, datetime-local, date, month, time, week, number, email, url, search, tel, and color.

//returns modal html from passed parameters
class FormGenerator {

        protected $CI;
        public function __construct()
        {
            $CI =& get_instance();
            //initialization parameters
            //id, title
        }



        public function create_button($id, $text, $btnId)
        {
            $tmp_html = `<button type='button' class='btn btn-primary' data-toggle='modal' data-target=$id data-whatever=$btnId>$text></button>`;
            return $tmp_html;
        }

        public function create_header($id, $text)
        {
            $id1 = $id."Modal";
            $tmp_html = `<div class="modal-header">
            <h5 class="modal-title" id=$id1>$text</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`;
            return $tmp_html;
        }

        public function create_text_input($id, $type, $text)
        {
            $tmp_html = `<div class="form-group"><label for=$id class="col-form-label">$text:</label><input type=$type class="form-control" id=$id></div>`;
            return $tmp_html;
        }
        
        public function create_textarea($id, $text)
        {
            $tmp_html = `<div class="form-group"><label for=$id class="col-form-label">$text:</label><textarea class="form-control" id=$id></textarea></div>`;
            return $tmp_html;
        }
        
        public function create_select_option($id, $text, $opt_arr)
        {
            $opt ='';
            $tmp_sel_start =  `<div class="form-group"><label for=$id class="col-form-label">$text :</label><select id=$id class="form-control">`;
            foreach($opt_arr as $val=>$text){$opt .= `<option value="">Select One</option>`;}
            $tmp_sel_end = `</select></div>`;
            return ($tmp_sel_start.$opt.$tmp_sel_end);
        }
        
        public function create_form($a){return (`<form>$a</form>`);}
        public function create_body($form){return (`<div class="modal-body">$form</div>`);}

        public function create_footer($btn_arr)
        {
            
            $start =`<div class="modal-footer">`;
            $btn='';
            foreach($btn_arr as $v)
            {
                $nature = $v['nature'];
                $text = $v['text'];
                $btn .=`<button type="button" class="btn $nature" data-dismiss="modal">$text</button>`;

            }
            $end = `</div>`;
            return ($start.$btn.$end);
        }

        public function create_modal($id)
        {
            $id1 = $id."Modal";
            $start = `<div class="modal fade" id=$id tabindex="-1" role="dialog" aria-labelledby=$id1 aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content">`;
            $content = `<div class="modal-content">$header.$body.$footer`;
            $end =`</div></div>`;
            return ($start.$content.$end);
        }
        
   
}
