<?php
include_once("./plugins/indextank_client.php");

function indextank_new_post($foruminfo, $threadinfo, $post, $postinfo)
{

    $api = new ApiClient("http://:QRV0uB+9WKo9LZ@2ehis.api.indextank.com");
    $index = $api->get_index("test");

    $data = array("text" => $post["title"] . " " . $post["message"],
                  "title" => $post["title"],
                  "content" => $post["message"],
                  "threadid" => $threadinfo["threadid"],
                  "post" => print_r($post, true),
                  "foruminfo" => print_r($foruminfo, true),
                  "threadinfo" => print_r($threadinfo, true),
                  "postinfo" => print_r($postinfo, true),
                  "all_field"=> "todos",
                  "username" => $post["username"]);

    $index->add_document($post["postid"], $data);
}


function indextank_search($_REQUEST, $vbulletin, $perpage, $vbphrase, $current_user)
{

    if ($_REQUEST['do'] == 'showresults')
    {
        $searchid = $vbulletin->GPC['searchid'];
        $api = new ApiClient("http://:QRV0uB+9WKo9LZ@2ehis.api.indextank.com");
        $index = $api->get_index("test");

        $db = $vbulletin->db;

        $sql ="
			SELECT criteria
			FROM " . TABLE_PREFIX . "searchlog
			WHERE userid = " . intval($current_user->get_field('userid')) . " AND
				searchlogid = " . intval($searchid) . " AND
				completed = 1";

        $row = $db->query_first($sql);

        $result_array = array();
		if (isset($row['criteria']))
		{
		    $criteria = unserialize($row['criteria']);
		    $query = $criteria->get_raw_keywords();

            $indextank_search_result = $index->search($query);
            $typeid = vB_Search_Core::get_instance()->get_contenttypeid('vBForum', 'Post');

            foreach($indextank_search_result->results as $indextank_result)
            {
                $result_array[] = array($typeid, $indextank_result->docid);
            }
        }

        $results = vB_Search_Results::create_from_array($current_user, $result_array);

        $base = 'search.php?' . $vbulletin->session->vars['sessionurl'] .
        'searchid=' . $vbulletin->GPC['searchid'] . '&amp;pp=' . $perpage;

        //note that show page will handle blank results just fine
        if ($results AND $results->get_criteria()->get_searchtype() == vB_Search_Core::SEARCH_NEW)
        {
            $navbits = array(
            'search.php' . $vbulletin->session->vars['sessionurl_q'] => $vbphrase['search'],
            '' => $vbphrase['new_posts_nav']
            );
        }
        else
        {
            $navbits = array(
            'search.php' . $vbulletin->session->vars['sessionurl_q'] => $vbphrase['search'],
            '' => $vbphrase['search_results']
            );
        }
        $show['popups'] = true;
        $view = new vb_Search_Resultsview($results);
        $view->showpage($vbulletin->GPC['pagenumber'], $vbulletin->GPC['perpage'], $base, $navbits);
    }
}

?>

