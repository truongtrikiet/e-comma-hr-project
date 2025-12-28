<?php

use App\Models\Candidate;
use App\Models\Document;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\Setting;
use App\Models\Template;
use App\Models\User;

// COMMON
if (!defined('NOTIFICATION_SUCCESS')) define('NOTIFICATION_SUCCESS', 'success');
if (!defined('NOTIFICATION_ERROR')) define('NOTIFICATION_ERROR', 'error');
// END COMMON

//-- User --
// if (!defined('USER_AVATAR_COLLECTION')) define('USER_AVATAR_COLLECTION', User::USER_AVATAR_COLLECTION);
// if (!defined('USER_AVATAR_RESIZE_NAME')) define('USER_AVATAR_RESIZE_NAME', User::USER_AVATAR_COLLECTION);
// if (!defined('USER_ID_CARD_FRONT_COLLECTION')) define('USER_ID_CARD_FRONT_COLLECTION', User::USER_ID_CARD_FRONT_COLLECTION);
// if (!defined('USER_ID_CARD_BACK_COLLECTION')) define('USER_ID_CARD_BACK_COLLECTION', User::USER_ID_CARD_BACK_COLLECTION);

//-- end User --

//-- Candidate --
// if (!defined('CANDIDATE_COLLECTION')) define('CANDIDATE_COLLECTION', Candidate::CANDIDATE_COLLECTION);
// if (!defined('CANDIDATE_RESIZE_NAME')) define('CANDIDATE_RESIZE_NAME', Candidate::CANDIDATE_RESIZE_NAME);
//-- end Candidate --

//-- Template --
// if (!defined('SEARCH_USER_TEMPLATE')) define('SEARCH_USER_TEMPLATE', 'SearchUser');
// if (!defined('SEARCH_CUSTOMER_TEMPLATE')) define('SEARCH_CUSTOMER_TEMPLATE', 'SearchCustomer');
// if (!defined('TEMPLATE_BACKGROUND_IMAGE_COLLECTION')) define('TEMPLATE_BACKGROUND_IMAGE_COLLECTION', Template::TEMPLATE_BACKGROUND_IMAGE_COLLECTION);
//-- end Template --

//-- Post --
// if (!defined('POST_FILE_UPLOAD_COLLECTION')) define('POST_FILE_UPLOAD_COLLECTION', Post::POST_FILE_UPLOAD_COLLECTION);
// if (!defined('ADD_COMMENT_POST')) define('ADD_COMMENT_POST', 'AddComment');
// if (!defined('ADD_EMOTE_POST')) define('ADD_EMOTE_POST', 'AddEmote');
// if (!defined('ADD_EMOTE_COMMENT')) define('ADD_EMOTE_COMMENT', 'AddEmoteComment');
// if (!defined('DELETE_EMOTE_COMMENT')) define('DELETE_EMOTE_COMMENT', 'DeleteEmoteComment');
// if (!defined('DELETE_EMOTE_POST')) define('DELETE_EMOTE_POST', 'DeleteEmote');
// if (!defined('POST_EXPAND')) define('POST_EXPAND', 'PostExpand');
// if (!defined('POST_RENDERED')) define('POST_RENDERED', 'PostRendered');
//-- end Post --

//-- Preference --
// if (!defined('BANNER_URL_COLLECTION')) define('BANNER_URL_COLLECTION', Setting::BANNER_URL_COLLECTION);
// if (!defined('KEY_JSON_COLLECTION')) define('KEY_JSON_COLLECTION', Setting::KEY_JSON_COLLECTION);
//-- end Preference --

//-- Project --
// if (!defined('PROJECT_AVATAR_COLLECTION')) define('PROJECT_AVATAR_COLLECTION', Project::PROJECT_AVATAR_COLLECTION);
//-- end Project --

//-- Project Document--
// if (!defined('PROJECT_DOCUMENT_COLLECTION')) define('PROJECT_DOCUMENT_COLLECTION', ProjectDocument::PROJECT_DOCUMENT_COLLECTION);
//-- end Project Document--


//-- Document --
// if (!defined('DOCUMENT_COLLECTION')) define('DOCUMENT_COLLECTION', Document::DOCUMENT_COLLECTION);
//-- end Document --
