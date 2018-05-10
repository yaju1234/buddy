<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Api_user_feed_model extends CI_Model

{

  public function fetchUserfeed($user_id,$pagination){
    

   
    $rows_followers = $this->db->query('SELECT following_user_id  as myfriendid FROM follow_friend WHERE follower_id = '.$user_id.' AND status = "1" UNION 
      SELECT sender_id  as myfriendid  FROM friends WHERE receiver_id = '.$user_id.' AND friend_status = "ACCEPTED" AND status = "1" UNION 
      SELECT receiver_id as myfriendid  FROM friends WHERE sender_id = '.$user_id.' AND friend_status = "ACCEPTED" AND status = "1" 
      ')->result_array();
    

    $str = implode(', ', array_map(function ($entry) {
     return $entry['myfriendid'];
    },$rows_followers));

    if($str == ''){
       $str = 0;
    }


    $row_user = $this->db->select('image,full_name,id')->where("id",$user_id)->get('users')->row_array();

    $rows_count = $this->db->query('select count(posts.id) 
                              from posts 
                              left join user_galleries ON posts.id = user_galleries.type_id AND type = "POST" AND user_galleries.status = "1" 
                              left join comments ON posts.id = comments.parent_content_id AND comments.parent_content_type = "POST" and comments.status = "1" 
                              left join likes ON posts.id = likes.parent_content_id  AND likes.reaction = "LIKE" and likes.status = "1" AND likes.parent_content_type = "POST" 
                              left join post_tag ON posts.id = post_tag.post_id AND post_tag.status = "1" 
                              left join users as tag_user ON post_tag.tagged_user_id = tag_user.id 
                              inner join users ON users.id = posts.user_id 
                              left join posts as motherpost ON posts.is_shared_from = motherpost.id  
                              left join users as users_m ON motherpost.user_id = users_m.id 
                              where posts.status = "1" and (posts.post_access_status = "FRIENDS" and posts.user_id in ('.$str.')) OR posts.post_access_status = "PUBLIC" OR post_tag.tagged_user_id = '.$user_id.'
                              group by posts.id ')->result_array();

    $rows_counter = count($rows_count);

    $limit = ' LIMIT '.($pagination * 10).',10';

    $rows = $this->db->query('select posts.*, 
                              users.id as postuserid,users.full_name,users.image,users.gender,   
                              users_m.id as m_postuserid,users_m.full_name m_full_name, users_m.image m_image,
                              user_galleries.media_type, user_galleries.media_link ,
                              group_concat(comments.id) as countercomment,  
                              group_concat(likes.id) as counterlike,
                              group_concat(likes.user_id) as useridlike,
                              group_concat(tag_user.id) as tag_uid,
                              group_concat(tag_user.full_name) as tag_uname,
                              group_concat(tag_user.image) as tag_uimg 
                              from posts 
                              left join user_galleries ON posts.id = user_galleries.type_id AND type = "POST" AND user_galleries.status = "1" 
                              left join comments ON posts.id = comments.parent_content_id AND comments.parent_content_type = "POST"  
                              left join likes ON posts.id = likes.parent_content_id  AND likes.reaction = "LIKE" and likes.status = "1" AND likes.parent_content_type = "POST" 
                              left join post_tag ON posts.id = post_tag.post_id AND post_tag.status = "1" 
                              left join users as tag_user ON post_tag.tagged_user_id = tag_user.id 
                              inner join users ON users.id = posts.user_id 
                              left join posts as motherpost ON posts.is_shared_from = motherpost.id  
                              left join users as users_m ON motherpost.user_id = users_m.id 
                              where posts.status = "1" and (posts.post_access_status = "FRIENDS" and posts.user_id in ('.$str.')) OR posts.post_access_status = "PUBLIC" OR post_tag.tagged_user_id = '.$user_id.'
                              group by posts.id  order by posts.created DESC'.$limit)->result_array();
 //  echo $this->db->last_query();

   $total_response = array();

  foreach($rows as $res){
        $result = array();


          // basic data
         $result['data_type'] = $res['data_type'];
         $result['post_id'] = (string)$res['id'];
         $result['content'] = $res['content'];
         $result['post_access_status'] = $res['post_access_status'];

         $date = date_create($res['created']);
         $result['post_date'] =  date_format($date,"dS M Y h:i:s A");
         $result['media_link'] = $res['media_link'] == null ? "" : $res['media_link'] ;
         $result['media_type'] = $res['media_type'] == null ? "" : $res['media_type'] ;
     
         // total like
        if($res['counterlike'] != ''){

          $result['total_like'] = (string)count(explode(',',$res['counterlike']));
          $result['userid_like'] = $res['useridlike'];

        }else{

          $result['total_like'] = "0";
          $result['userid_like'] = "";

        }

         // total comment
        if($res['countercomment'] != ''){

          $result['total_comment'] = (string)count(explode(',',$res['countercomment']));
        }else{
          $result['total_comment'] = "0";
        }

        // post user
        $result['post_user'] = array('post_user_id' => (string)$res['postuserid'],'post_user_name'=>$res['full_name'],'post_user_image'=>$res['image']== null ? "" : $res['image']);


        // shared post
        if($res['is_shared_from'] != ''){

          $result['post_title'] = $res['full_name'].' shared '.$res['m_full_name'].'\'s post ';
          $result['post_user_from'] = array('post_user_from_id' => (string)$res['m_postuserid'],'post_user_from_name'=>$res['m_full_name'],'post_user_from_image'=>$res['m_image']== null ? "" : $res['m_image']);
        }else{

            $gender = ($res['gender'] == "F") ? 'her': 'his';
            $result['post_user_from'] = array('post_user_from_id' => "",'post_user_from_name'=>"",'post_user_from_image'=>"");
            $result['post_title'] = $res['full_name'].' posted on '. $gender.' own timeline';
        }
        
        // tagged user
        $tagged_user = array();
        if($res['tag_uid'] != ''){

          $tagged = explode(',',$res['tag_uid']);
          $taggedname = explode(',',$res['tag_uname']);
          $t_count = 0;
          foreach($tagged as $tag){
            $tagged_user[] = array('tagged_user_id' => (string)$tag,'tagged_user_name' => $taggedname[$t_count]);
            $t_count++;
          }
          $result['tagged_user'] = $tagged_user;
          $result['tagged_user_counter'] = (string)count($tagged);


          if (in_array($user_id,$tagged)){
            $result['post_title'] = 'You are dedicated in '. $res['full_name'].'\'s post';
          }
         }else{

          $result['tagged_user'] = array();
          $result['tagged_user_counter'] = "0";
        }
     
      $total_response[] =  $result;
    }


    $my_response['total_counter'] = $rows_counter;
    $my_response['feed_details'] = $total_response;
    $my_response['current_user'] = $row_user;
    return ($my_response);
 
  }


  public function postUsercomment($data){
      $this->db->insert('comments',$data);
      return $this->db->insert_id();
  }

  public function postUserlike($data){
      
       $rows = $this->db->select('*')
       ->where("parent_content_id",$data['parent_content_id'])
       ->where("user_id",$data['user_id'])
       ->where("parent_content_type",$data['parent_content_type'])->get('likes')->row_array();


      if(count($rows) == 0){

        $this->db->insert('likes',$data);
        return $this->db->insert_id();

     }else{

        $updata['reaction']= $data['reaction'];
        $this->db->where('id',$rows['id'])->update('likes',$updata);
        return $rows['id'];

     }

      
  }

  public function sharePosts($userId,$postId){

    $rows = $this->db->select('*')->where("id",$postId)->get('posts')->row_array();

    $insert['user_id'] = $userId;
    $insert['content'] = $rows['content'];
    $insert['post_access_status'] = $rows['post_access_status'];
    $insert['data_type'] = $rows['data_type'];
    $insert['created'] = $rows['created'];
    $insert['user_timeline'] = $rows['user_timeline'];
    $insert['is_shared_from'] = $rows['user_id'];
    $this->db->insert('posts',$insert);

    $lastId =  $this->db->insert_id();


    $row_post = $this->db->query('select posts.*, 
                              users.id as postuserid,users.full_name,users.image,users.gender,   
                              users_m.id as m_postuserid,users_m.full_name m_full_name, users_m.image m_image,
                              user_galleries.media_type, user_galleries.media_link ,
                              group_concat(comments.id) as countercomment,  
                              group_concat(likes.id) as counterlike,
                              group_concat(likes.user_id) as useridlike,
                              group_concat(tag_user.id) as tag_uid,
                              group_concat(tag_user.full_name) as tag_uname,
                              group_concat(tag_user.image) as tag_uimg 
                              from posts 
                              left join user_galleries ON posts.id = user_galleries.type_id AND type = "POST" AND user_galleries.status = "1" 
                              left join comments ON posts.id = comments.parent_content_id AND comments.parent_content_type = "POST"  
                              left join likes ON posts.id = likes.parent_content_id  AND likes.reaction = "LIKE" and likes.status = "1" AND likes.parent_content_type = "POST" 
                              left join post_tag ON posts.id = post_tag.post_id AND post_tag.status = "1" 
                              left join users as tag_user ON post_tag.tagged_user_id = tag_user.id 
                              inner join users ON users.id = posts.user_id 
                              left join posts as motherpost ON posts.is_shared_from = motherpost.id  
                              left join users as users_m ON motherpost.user_id = users_m.id 
                              where posts.id = '.$lastId)->row_array();


   $total_response = array();


  }



  public function friend_list_post($user_id){

      $row = $this->db->query('SELECT id, IF(receiver_id = 83, sender_id, receiver_id) AS myfriends FROM friends WHERE friends.receiver_id = 83 OR friends.sender_id = 83 ')->row_array();

  }


}

?>
