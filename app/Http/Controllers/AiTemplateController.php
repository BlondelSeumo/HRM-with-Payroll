<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orhanerday\OpenAi\OpenAi;

class AiTemplateController extends Controller
{
    public function create($moduleName)
    {
        $templateName = Template::where('module', $moduleName)->get();
        return view('template.generate', compact('templateName'));
    }

    public function getKeywords(Request $request, $id)
    {
        $template = Template::find($id);
        $field_data = json_decode($template->field_json);
        $html = "";
        foreach ($field_data->field as  $value) {
            $html .= '<div class="form-group col-md-12">
                     <label class="form-label ">' . $value->label . '</label>';
            if ($value->field_type == "text_box") {

                $html .= '<input type="text" class="form-control" name="' . $value->field_name . '" value="" placeholder="' . $value->placeholder . '" required">';
            }
            if ($value->field_type == "textarea") {
                $html .= '<textarea type="text" rows=3 class="form-control " id="description" name="' . $value->field_name . '" placeholder="' . $value->placeholder . '" required></textarea>';
            }
            $html .= '</div>';
        }
        return response()->json(
            [
                'success' => true,
                'tone' => $template->is_tone,
                'template' => $html,
            ]
        );
    }

    public function AiGenerate(Request $request)
    {
        if ($request->ajax()) {
            $post = $request->all();

            unset($post['_token'], $post['template_name'], $post['tone'], $post['ai_creativity'], $post['num_of_result'], $post['result_length']);
            $data = array();
            $key_data = DB::table('settings')->where('name', 'chatgpt_key')->first();

            if ($key_data) {

                $open_ai = new OpenAi($key_data->value);
            } else {
                $data['status'] = 'error';
                $data['message'] = __('Please set proper configuration for Api Key');
                return $data;
            }

            $prompt = '';
            $model = '';
            $text = '';
            $ai_token = '';
            $counter = 1;


            $template = Template::where('id', $request->template_name)->first();

            if ($request->template_name) {


                $required_field = array();
                $data_field = json_decode($template->field_json);
                foreach ($data_field->field as  $val) {
                    request()->validate([$val->field_name => 'required|string']);
                }

                $prompt = $template->prompt;
                foreach ($data_field->field as  $field) {

                    $text_rep = "##" . $field->field_name . "##";
                    if (strpos($prompt, $text_rep) !== false) {
                        $field->value = $post[$field->field_name];
                        $prompt = str_replace($text_rep, $post[$field->field_name], $prompt);
                    }
                    if ($template->is_tone == 1) {
                        $tone = $request->tone;
                        $param = "##tone_language##";
                        $prompt = str_replace($param, $tone, $prompt);
                    }
                }
            }
            $lang_text = "Provide response in " . $request->language . " language.\n\n ";
            $ai_token = (int)$request->result_length;

            $max_results = (int)$request->num_of_result;
            $ai_creativity = (float)$request->ai_creativity;
            $settings = Utility::getChatGPTSetting();
            $complete = $open_ai->completion([
                'model' => isset($settings['chatgpt_model']) ? $settings['chatgpt_model'] : '',
                'prompt' => $prompt . ' ' . $lang_text,
                'temperature' => $ai_creativity,
                'max_tokens' => $ai_token,
                'n' => $max_results
            ]);

            $response = json_decode($complete, true);
            if (isset($response['choices'])) {
                if (count($response['choices']) > 1) {
                    foreach ($response['choices'] as $value) {
                        $text .= $counter . '. ' . ltrim($value['text']) . "\r\n\r\n\r\n";
                        $counter++;
                    }
                } else {
                    $text = $response['choices'][0]['text'];
                }

                $tokens = $response['usage']['completion_tokens'];
                $data = trim($text);

                return $data;
            } else {
                $data['status'] = 'Error';
                $data['message'] = $response['error']['message'];
                return $data;
            }
        }
    }

    //for grammar check
    public function grammar($moduleName)
    {
        $templateName = Template::where('module', $moduleName)->first();
        return view('template.grammar_ai', compact('templateName'));
    }

    public function grammarProcess(Request $request)
    {
        if ($request->ajax()) {
            $post = $request->all();
            
            unset($post['_token'], $post['template_name'], $post['tone'], $post['ai_creativity'], $post['num_of_result'], $post['result_length']);
            $data = array();
            $key_data = DB::table('settings')->where('name', 'chatgpt_key')->first();

            if ($key_data) {
                $open_ai = new OpenAi($key_data->value);
            } else {
                $data['status'] = 'error';
                $data['message'] = __('Please set proper configuration for Api Key');
                return $data;
            }

            $counter = 1;
            $prompt = "please correct grammar mistakes and spelling mistakes in this: . $request->description .";
            $is_tone = 1;
            $ai_token = strlen($request->description);
            $max_results = 1;
            $ai_creativity = 1.0;
            $settings = Utility::getChatGPTSetting();
            $complete = $open_ai->completion([
                'model' => isset($settings['chatgpt_model']) ? $settings['chatgpt_model'] : '',
                'prompt' => $prompt,
                'temperature' => $ai_creativity,
                'max_tokens' => $ai_token,
                'n' => $max_results
            ]);
            $response = json_decode($complete, true);
            if (isset($response['choices'])) {
                if (count($response['choices']) > 1) {
                    foreach ($response['choices'] as $value) {
                        $text .= $counter . '. ' . ltrim($value['text']) . "\r\n\r\n\r\n";
                        $counter++;
                    }
                } else {
                    $text = $response['choices'][0]['text'];
                }
                $tokens = $response['usage']['completion_tokens'];
                $data = trim($text);
                return $data;
            } else {
                $data['status'] = 'Error';
                $data['message'] = $response['error']['message'];
                return $data;
            }
        }
    }
    
}
