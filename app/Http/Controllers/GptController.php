<?php

namespace App\Http\Controllers;

use App\Helpers\Sys_helper;
use App\Models\Editor;
use App\Models\Ia_credential;
use App\Models\Wp_post_content;
use App\Services\GptService;
use App\Services\Wp_service;
use Illuminate\Http\Request;
use  Exception;

class GptController extends Controller
{
    //
    protected $gptService;
    protected $wpService;
    protected $helper;


    public function __construct(GptService $gptService, Wp_service $blogService)
    {
        $this->gptService=$gptService;
        $this->wpService=$blogService;
    }

    public function replace_variables($commands, $variables) {
        foreach ($commands as &$command) {
            $command = str_replace(
                array_map(function ($var) { return "%%$var%%"; }, array_keys($variables)),
                array_values($variables),
                $command
            );
        }
        return $commands;
    }

    public function generatePost(Request $request){
        $arr = [];
        foreach($request->title as $key=>$topic){
            $id=isset($request->id[$key])?$request->id[$key]:null;
            $response=$this->gptThread($id,$topic);
            $arr[] = $response;
        }
        return $arr;
    }




    public function gptThread($id,$post_title){
        $gptData='';
        $valorCodificado = request()->cookie('editor');
        $user=explode('+',base64_decode($valorCodificado));
        $editor=Editor::where('name',$user)->get();
        $token=Editor::find($editor[0]->id)->iaCredentials;
        $Google_api_key=isset(Editor::all()->first()->GoogleCredentials->api_key)?Editor::all()->first()->GoogleCredentials->api_key:null;
        $title = $post_title;
        $language = $token->language;
        $writing_style = $token->wrinting_style;
        $writing_tone = $token->writing_tone;
        $sections_count = intval($token->sections);
        $paragraphs_per_section = intval($token->paragraphs);
        $keyword=null;
        $anchor_1=null;
        $anchor_1_url=null;
        $anchor_2_url=null;
        $anchor_3_url=null;
        $do_follow_link_1=null;
        $do_follow_link_2=null;
        $do_follow_link_3=null;
        $anchor_2=null;
        $anchor_3=null;
        $id_content=null;
        $video=null;
        $token=$token->open_ai;
        //return json_encode('chegou aqui');

            $data=[];
            foreach(Wp_post_content::where('theme', $post_title)->orWhere('id', $id)->get() as $post_config){

                $keyword=$post_config->keyword;
                $anchor_1=$post_config->anchor_1;
                $anchor_2=$post_config->anchor_2;
                $anchor_3=$post_config->anchor_3;
                $id_content=$post_config->id;
                $anchor_1_url=$post_config->url_link_1;
                $anchor_2_url=$post_config->url_link_2;
                $anchor_3_url=$post_config->url_link_3;
                $do_follow_link_1=$post_config->do_follow_link_1;
                $do_follow_link_2=$post_config->do_follow_link_2;
                $do_follow_link_3=$post_config->do_follow_link_3;
                $video=$post_config->video;
            }

            //dd($data);





        $commands=array(
            'intro'=>'Write an intro for an article about "%%title%%", in %%language%%. Style: %%writing_style%%. Tone: %%writing_tone%%.',
            'section'=>'Write %%sections_count%% consecutive headings for an article about "%%title%%" that highlight specific aspects, provide detailed insights and specific recommendations. The Keyword for this text is %%Keyword%%. The headings should be written in %%language%%, following a %%writing_style%% style and a %%writing_tone%% tone. Dont add numbers to the headings or any types of quotes. Return only the headings list, nothing else.',

        );

        $clean_command=$this->replace_variables($commands,array(
            'title' => $title,
            'language' => $language,
            'writing_style' => $writing_style,
            'writing_tone' => $writing_tone,
            'sections_count' => $sections_count,
            'paragraphs_per_section' => $paragraphs_per_section,
            'Keyword'=>$keyword,



        ));

        $total_comands=[];
        $complete_post=[];
        $total_comands[]=$clean_command;


        for($i=0;$i<$sections_count;$i++){
            $sections=['Write the content of a post section for the heading "%%current_section%%" in %%language%%. The title of the post is: "%%title%%". This content must have keywords %%Ancora 1%%, %%Ancora 2%% and %%Ancora 3%%. Dont add the title at the beginning of the created content. Be creative and unique. Dont repeat the heading in the created content. Dont add an intro or outro. Write %%paragraphs_per_section%% in the section, each paragraph must have paragraphs 400 words. Use HTML for formatting, include unnumbered lists and bold. Writing Style: %%writing_style%%. Tone: %%writing_tone%%. For %%Ancora 1%%  add this element into the text: <a href="%%Anchor_link_1%%" rel="%%Follow_1%%follow">%%Ancora 1%%</a>, for %%Ancora 2%% add this element into the text: <a href="%%Anchor_link_2%%" rel="%%Follow_2%%follow">%%Ancora 2%%</a>,for %%Ancora 3%% add this element into the text: <a href="%%Anchor_link_3%%" rel="%%Follow_3%%follow">%%Ancora 3%%</a>'];
            // $sections=['Write the content of a post section for the heading "%%current_section%%" in %%language%%. The title of the post is: "%%title%%". This content must have keywords %%Ancora 1%%, %%Ancora 2%% and %%Ancora 3%%. add a <h2> title at the beginning of the created content. Be creative and unique. Dont repeat the heading in the created content. Dont add an intro or outro. Write %%paragraphs_per_section%% in the section, each paragraph must have paragraphs 400 words. Use HTML for formatting, include unnumbered lists and bold. Writing Style: %%writing_style%%. Tone: %%writing_tone%%. For %%Ancora 1%%  add this element into the text: <a href="%%Anchor_link_1%%" rel="%%Follow_1%%follow">%%Ancora 1%%</a>, for %%Ancora 2%% add this element into the text: <a href="%%Anchor_link_2%%" rel="%%Follow_2%%follow">%%Ancora 2%%</a>,for %%Ancora 3%% add this element into the text: <a href="%%Anchor_link_3%%" rel="%%Follow_3%%follow">%%Ancora 3%%</a>'];
            $complete_text=$this->replace_variables($sections,array(
                'current_section'=>$i,
                'Ancora 1'=>$anchor_1,
                // 'Anchor_link_1'=>$anchor_1_url,
                // 'Anchor_link_2'=>$anchor_2_url,
                // 'Anchor_link_3'=>$anchor_3_url,
                'Ancora 2'=>$anchor_2,
                'Ancora 3'=>$anchor_3,
                // 'Follow_1'=>($do_follow_link_1==1)?'do':'no',
                // 'Follow_2'=>($do_follow_link_2==1)?'do':'no',
                // 'Follow_3'=>($do_follow_link_3==1)?'do':'no',
                'language' => $language,
                'title' => $title,
                'writing_style' => $writing_style,
                'writing_tone' => $writing_tone,
                'paragraphs_per_section' => $paragraphs_per_section,
            ));
            $total_comands[]=$complete_text;

        }
        foreach($total_comands[0] as $command){
                $max_retries = 10;
                $data = null;

                for ($i = 0; $i < $max_retries; $i++) {
                    $gpt_request = $this->gptService->sendRequest($command, $post_title, $token);

                    if ($gpt_request['choices'][0]['message']['content'] != 0) {
                        $data = $gpt_request['choices'][0]['message']['content'];
                        break;
                    }
                }

                // se ainda não der certo
                if ($data === null) {
                    throw new Exception("Tentativas ultrapassaram os limites");
                }

                $complete_post[]=$data;
                $gptData.=$data."\n\n";
            }
        $headings = explode("\n", $complete_post[1]);
        $filtered_array=array_values(array_filter($headings));
        // dd($filtered_array);

        foreach($filtered_array as $key=>$heading){
            // dd($heading);
            $max_retries = 10;
            $content = null;

            for ($i = 0; $i < $max_retries; $i++) {
                $section_request = $this->gptService->sendRequest($total_comands[$key+1][0], $heading, $token);

                if ($section_request['choices'][0]['message']['content'] != 0) {
                    $content = $section_request['choices'][0]['message']['content'];
                    break;
                }
            }

            // If after all retries, the content is still null, throw an error
            if ($content === null) {
                throw new Exception("Tentativas ultrapassaram os limites");
            }

            $complete_post[]="<h2>$heading</h2>\n\n".$content;
            $gptData.="<h2>$heading</h2>\n\n";
            $gptData.=$content."\n\n";
        }
        $new_value=str_replace($filtered_array[0],"",$gptData);
        $newGptData=str_replace($filtered_array[1],"",$gptData);





        $qa_command=$this->replace_variables(['Write a Q&A for an article about "%%title%%", in %%language%%. Style: %%writing_style%%. Tone: %%writing_tone%%. This Q&A must have keywords %%Ancora 1%%, %%Ancora 2%% and %%Ancora 3%%'],array(

            'language' => $language,
            'title' => $title,
            'writing_style' => $writing_style,
            'writing_tone' => $writing_tone,
            'Ancora 1'=>$anchor_1,
            'Ancora 2'=>$anchor_2,
            'Ancora 3'=>$anchor_3,
        ));



        $conclusion_command=$this->replace_variables( ['Write an outro for an article about "%%title%%", in %%language%%. Style: %%writing_style%%. Tone: %%writing_tone%%'],array(
            'language' => $language,
            'title' => $title,
            'writing_style' => $writing_style,
            'writing_tone' => $writing_tone,
        ));

        if($video==1){
            $videoLink=$this->searchYouTubeAndGetURL($Google_api_key,$keyword);
            if($videoLink != '' || $videoLink != 'https://www.youtube.com/embed/')
            {
                $videoEmbedd=$this->convertYouTubeLinksToEmbeds($videoLink);
                $newGptData.=$videoEmbedd."\n\n";
            }
        }

        $qa_title="<h2>Perguntas & respostas</h2>"."\n\n";

        $qa_request=$this->gptService->sendRequest($qa_command[0],$heading,$token);
        $complete_post[]=$qa_request['choices'][0]['message']['content'];
        $newGptData.=$qa_title;
        $newGptData.=$qa_request['choices'][0]['message']['content']."\n\n";


        $conclusion_request=$this->gptService->sendRequest($conclusion_command[0],$heading,$token);
        $complete_post[]=$conclusion_request['choices'][0]['message']['content'];
        $newGptData.=$conclusion_request['choices'][0]['message']['content']."\n\n";
        $dataParsed=$this->replace_variables([$newGptData],array(
                'Anchor_link_1'=>$anchor_1_url,
                'Anchor_link_2'=>$anchor_2_url,
                'Anchor_link_3'=>$anchor_3_url,
                'Follow_1'=>($do_follow_link_1==1)?'do':'no',
                'Follow_2'=>($do_follow_link_2==1)?'do':'no',
                'Follow_3'=>($do_follow_link_3==1)?'do':'no',
        ));
        //dd($dataParsed[0]);
        $dataParsed[0] = $this->removeDuplicateHref($dataParsed[0]);
        //dd($dataParsed[0]);

        $insertPostContent=Wp_post_content::where('id',$id_content)->update(['post_content'=>$dataParsed[0]]);
        return $insertPostContent;
    }
    function removeDuplicateH2($htmlString) {
        // Regular expression to match <h2> tags
        $pattern = '/<h2[^>]*>(.*?)<\/h2>/i';

        // Callback function to process each match
        $callback = function($matches) {
            static $uniqueTitles = array();
            $title = $matches[0]; // Whole matched <h2> tag
            $innerText = $matches[1]; // Inner text of the <h2> tag

            // Check if this title is already present in the array
            if (!in_array($innerText, $uniqueTitles)) {
                // If not, add it to the array
                $uniqueTitles[] = $innerText;
                return $title; // Return the matched <h2> tag unchanged
            } else {
                // If it is, return an empty string
                return '';
            }
        };

        // Perform the regular expression replacement
        $modifiedHtml = preg_replace_callback($pattern, $callback, $htmlString);

        // Return the modified HTML string
        return $modifiedHtml;
    }

    function removeDuplicateHref($htmlString) {
        // Regular expression to match <a> tags with href attribute
        $pattern = '/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/i';

        // Callback function to process each match
        $callback = function($matches) {
            static $uniqueHrefs = array();
            $href = $matches[1];
            $innerText = $matches[2];


            // Check if href is empty or matches a pattern of not containing a link
            if (empty($href) || !preg_match('/^((http|https|ftp):\/\/[^\s$.?#].[^\s]*)?$/i', $href)) {
                // If href is empty or invalid, return inner text
                return $innerText;
            }
            // Check if this href value is already present in the array
            if (!in_array($href, $uniqueHrefs)) {
                // If not, add it to the array
                $uniqueHrefs[] = $href;
                return $matches[0]; // Return the matched <a> tag unchanged
            } else {
                // If it is, return only the innerText
                return $innerText;
            }
        };

        // Perform the regular expression replacement
        $modifiedHtml = preg_replace_callback($pattern, $callback, $htmlString);

        // Return the modified HTML string
        return $modifiedHtml;
    }
    //Pode usar para converter um padrão no complete_post[] em youtube embeds
    public function convertYouTubeLinksToEmbeds($string) {
        // Padrão
        $pattern = '/<a\s+(?:[^>]*?\s+)?href=(["\'])(https?:\/\/(?:www\.)?youtube\.com\/watch\?v=[\w-]+)(?(1)\1|)(?:[^>]*?\s+)?rel=(["\'])([^"\']*)\3[^>]*?>(.*?)<\/a>/i';

        // para substituir
        //$replacement = '<iframe width="560" height="315" src="'.$string.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
        $replacement = '<a href="'.$string.'" rel="dofollow">youtube link</a>';
        //filter_var($replacement, FILTER_SANITIZE_STRING);

        //$convertedString = preg_replace($pattern, $replacement, $string);
        //dd($replacement);
        return $replacement;
    }
    // Pesquisar o primeiro link do youtube
    public function searchYouTubeAndGetURL($api_key,$query) {
         if($api_key == ''){
              return '';
          }
        $apiKey = $api_key;
        $apiEndpoint = 'https://www.googleapis.com/youtube/v3/search';

        $params = array(
            'q' => $query,
            'key' => $apiKey,
            'part' => 'snippet',
            'maxResults' => 1
        );
        //dd($query,$apiKey);
        try {
            $url = $apiEndpoint . '?' . http_build_query($params);
            $response = file_get_contents($url);
        } catch (Exception $e) {
            dd($url, $e);
        }

        $responseData = json_decode($response, true);
        $videoId='';
        if (isset($responseData['items'][0]['id']['videoId'])) {
            $videoId = $responseData['items'][0]['id']['videoId'];
            $videoURL = 'https://www.youtube.com/embed/'.$videoId;
            return $videoURL;
        } else {
            return ''; // Nenhum video encontrado
        }
    }

}
