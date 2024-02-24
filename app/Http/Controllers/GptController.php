<?php

namespace App\Http\Controllers;

use App\Helpers\Sys_helper;
use App\Models\Wp_post_content;
use App\Services\GptService;
use App\Services\Wp_service;
use Illuminate\Http\Request;

class GptController extends Controller
{
    //
    protected $gptService;
    protected $wpService;
    protected $helper;

    
    public function __construct(GptService $gptService, Wp_service $blogService,Sys_helper $helper)
    {
        $this->gptService=$gptService;
        $this->wpService=$blogService;
        $this->helper=$helper;
    }


    public function generatePost(Request $request){
        $gptData='';
        
        $title = $request->topic;
        $language = $request->languages;
        $writing_style = $request->style;
        $writing_tone = $request->writing_tone;
        $sections_count = intval($request->sections);
        $paragraphs_per_section = intval($request->paragraphs);
        $key=null;
        $anchor_1=null;
        $anchor_1_url=null;
        $anchor_2=null;
        $anchor_3=null;
        $id_content=null;
        $token='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';


        foreach(Wp_post_content::where('id',intval($request->id))->get() as $post_config){
            
            
            $key=$post_config->keyword;
            $anchor_1=$post_config->anchor_1;
            $anchor_2=$post_config->anchor_2;
            $anchor_3=$post_config->anchor_3;
            $id_content=$post_config->id;
            $anchor_1_url=$post_config->url_link_2;
        }



        $commands=array(
            'intro'=>'Write an intro for an article about "%%title%%", in %%language%%. Style: %%writing_style%%. Tone: %%writing_tone%%.',
            'section'=>'Write %%sections_count%% consecutive headings for an article about "%%title%%" that highlight specific aspects, provide detailed insights and specific recommendations. The Keyword for this text is %%Keyword%%. The headings should be written in %%language%%, following a %%writing_style%% style and a %%writing_tone%% tone. Dont add numbers to the headings or any types of quotes. Return only the headings list, nothing else.',

        );

        $clean_command=$this->helper->replace_variables($commands,array(
            'title' => $title,
            'language' => $language,
            'writing_style' => $writing_style,
            'writing_tone' => $writing_tone,
            'sections_count' => $sections_count,
            'paragraphs_per_section' => $paragraphs_per_section,
            'Keyword'=>$key,



        ));

        //dd($clean_command);  
        $total_comands=[];
        $complete_post=[];
        $total_comands[]=$clean_command;


        for($i=0;$i<$sections_count;$i++){
            $sections=['Write the content of a post section for the heading "%%current_section%%" in %%language%%. The title of the post is: "%%title%%". This content must have keywords %%Ancora 1%%, %%Ancora 2%% and %%Ancora 3%%. Dont add the title at the beginning of the created content. Be creative and unique. Dont repeat the heading in the created content. Dont add an intro or outro. Write %%paragraphs_per_section%% paragraphs in the section. Use HTML for formatting, include unnumbered lists and bold. Writing Style: %%writing_style%%. Tone: %%writing_tone%%. For %%Ancora 1%% create this element in some parte of text <a href="%%Anchor_link%%" no-follow>%%Ancora 1%%</a>'];
            $complete_text=$this->helper->replace_variables($sections,array(
                'current_section'=>$i,
                'Ancora 1'=>$anchor_1,
                'Anchor_link'=>$anchor_1_url,
                'Ancora 2'=>$anchor_2,
                'Ancora 3'=>$anchor_3,
                'language' => $language,
                'title' => $title,
                'writing_style' => $writing_style,
                'writing_tone' => $writing_tone,
                'paragraphs_per_section' => $paragraphs_per_section,
            ));
            $total_comands[]=$complete_text;

        }

        foreach($total_comands[0] as $command){
            $gpt_request=$this->gptService->sendRequest($command,$request->topic,$token);
            $data=$gpt_request['choices'][0]['message']['content'];
            $complete_post[]=$data;
            $gptData.=$data;
            }

        $headings = explode("\n", $complete_post[1]);
        $filtered_array=array_values(array_filter($headings));
        //dd($filtered_array);
     
        foreach($filtered_array as $key=>$heading){

            $section_request=$this->gptService->sendRequest($total_comands[$key+1][0],$heading,$token);
            $complete_post[]=$section_request['choices'][0]['message']['content'];
            $gptData.=$section_request['choices'][0]['message']['content'];
        }


        $qa_command=$this->helper->replace_variables(['Write a Q&A for an article about "%%title%%", in %%language%%. Style: %%writing_style%%. Tone: %%writing_tone%%. This Q&A must have keywords %%Ancora 1%%, %%Ancora 2%% and %%Ancora 3%%'],array(

            'language' => $language,
            'title' => $title,
            'writing_style' => $writing_style,
            'writing_tone' => $writing_tone,
            'Ancora 1'=>$anchor_1,
            'Ancora 2'=>$anchor_2,
            'Ancora 3'=>$anchor_3,
        ));


        
        $conclusion_command=$this->helper->replace_variables( ['Write an outro for an article about "%%title%%", in %%language%%. Style: %%writing_style%%. Tone: %%writing_tone%%'],array(
            'language' => $language,
            'title' => $title,
            'writing_style' => $writing_style,
            'writing_tone' => $writing_tone,
        ));


        $qa_request=$this->gptService->sendRequest($qa_command[0],$heading,$token);
        $complete_post[]=$qa_request['choices'][0]['message']['content'];
        $gptData.=$qa_request['choices'][0]['message']['content'];


        $conclusion_request=$this->gptService->sendRequest($conclusion_command[0],$heading,$token);
        $complete_post[]=$conclusion_request['choices'][0]['message']['content'];
        $gptData.=$conclusion_request['choices'][0]['message']['content'];
        
        $insertPostContent=Wp_post_content::where('id',$id_content)->update(['post_content'=>$gptData]);
        return $insertPostContent;
    }
}
