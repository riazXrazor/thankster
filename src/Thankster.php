<?php

namespace Riazxrazor\Thankster;

use GuzzleHttp\Client;

class Thankster
{

    const BASE_URI = 'http://www.thankster.com/api/v1/';
    public $API_KEY = '';
    public $DEBUG = true;
    // GuzzleHttp client for making http requests
    /**
     * Guzzle response
     */
    public $responseData;
    public $rawResponse;

    /**
     * @var
     */
    public $userId;
    public $project_id;
    public $thanksterRecipientID;
    public $thanksterProjectID;
    public $thanksterProjectName;
    public $sender_id;
    public $thanksterOrderID;
    public $userEmail;


    protected $httpClient;

    /**
     * Initialize the guzzlehttp client
     *
     * This would be the id returned by the registration process
     */
    public function __construct($config)
    {
        foreach ($config as $key => $value)
        {
            $this->{$key} = $value;
        }

        $this->httpClient = new Client([
            'base_uri' => self::BASE_URI,
            'timeout' => 10
        ]);
    }

    /**
     * Load, create, or update a user account.
     *
     * Accounts are created using the email address as the primary key.
     * You must also provide the user's mailing address when making this call.
     * This ensures the address is pre-filled on all envelopes for the user's order.
     */
    public function findOrCreateUserByEmail($userData)
    {

        $this->apiCall('POST','api_users/findOrCreateUserByEmail',$userData);

        // Check if it is a valid response from thankster
        if ($this->responseData->status == 200) {
            $this->userId = $this->responseData->user_id;
        } else {
            throw new \Exception($this->responseData->message);
        }

        return $this;

    }

    /**
     *
     * This creates a new Project with a single card in it.
     * More cards may be added to this project later using the addCardRecipient method (future development).
     * The template ID provides the design of the card. From here, other API calls can be made to change this individual card.
     */

    public function createCardProject($registeredUserData)
    {

        $this->apiCall('POST','api_projects/createCardProject',$registeredUserData);

        if ($this->responseData->status == 200) {
                $this->project_id = $this->responseData->project_id;
                $this->thanksterRecipientID = $this->responseData->recipient_id;
                $this->thanksterProjectID = $this->responseData->received_project_id;
                $this->thanksterProjectName = $this->responseData->project_name;
                $this->sender_id = $this->responseData->sender_id;
        } else {
            throw new \Exception($this->responseData->message);
        }
        return $this;
    }

    /**
     * @param $templateId
     * @throws Exception
     * Messages are stored separately for each recipient in a project. To change messages you must provide the recipient ID and the user ID.
     */

    public function applyMessages($messageData)
    {
        $this->apiCall('POST','api_projects/applyMessages',$messageData);
        return $this;
    }

    /**
     * Change internal project name that will be used within Thankster
     * rename project is optional......
     * This is an advanced optional call, if this call is not used the project will be created with a generic name like "Project 1".
     */

    public function renameProject($data)
    {
//        $postdata = [
//            'thanksterProjectID' => $this->thanksterProjectID,
//            'thanksterUserID' => $this->userId,
//            'thanksterProjectName' => $newProjectName
//        ];

        $this->apiCall('POST','api_projects/renameProject',$data);
        return $this;
    }

    /**
     * Change the card design selected for this use on this project
     *
     * This is an optional call, if this call is not used the project will be created with the default of our system or the one selected for your template (if you have one).
     * @param $thanksterCardID
     */
    public function selectCard($data)
    {
//        $postdata = [
//            'thanksterProjectID' => $this->thanksterProjectID,
//            'thanksterUserID' => $this->userId,
//            'thanksterCardID' => $thanksterCardID
//        ];

        $this->apiCall('POST','api_projects/selectCard',$data);
        return $this;
    }

    /**Change the font selected for a the recipient of this project
     *
     * This is an optional call, if this call is not used the recipients will be created with the default of our system or the one selected for your template. (if you have one).
     * @param $thanksterFontID
     */

    public function selectFont($data)
    {
//        $postdata = [
//            'thanksterRecipientID' => $this->thanksterRecipientID,
//            'thanksterUserID' => $this->userId,
//            'thanksterFontID' => $thanksterFontID
//        ];

        $this->apiCall('POST','api_projects/selectFont',$data);
        return $this;
    }

    /**Change the font size for a the recipient of this project
     *
     * This is an optional call, if this call is not used the recipients will be created with the default font selected for your template.
     * @param $thanksterFontSize
     */

    public function selectFontSize($data)
    {
//        $postdata = [
//            'thanksterRecipientID' => $this->thanksterRecipientID,
//            'thanksterUserID' => $this->userId,
//            'thanksterFontSize' => $thanksterFontSize
//        ];

        $this->apiCall('POST','api_projects/selectFontSize',$data);
        return $this;
    }

    /**
     * Change the line angle for a the recipient of this project
     *
     * This is an optional call, if this call is not used the recipients will be created with the default line angle selected for your template.
     */
    public function selectLineAngle($data)
    {
//        $postdata = [
//            'thanksterRecipientID' => $this->thanksterRecipientID,
//            'thanksterUserID' => $this->userId,
//            'thanksterLineAngle' => $thanksterLineAngle,
//        ];

        $this->apiCall('POST','api_projects/selectLineAngle',$data);
        return $this;
    }

    /**Change the font color for a the recipient of this project
     *
     * This is an optional call, if this call is not used the recipients will be created with the default font selected for your template.
     * @param $thanksterFontColor
     */

    public function selectFontColor($data)
    {
//        $postdata = [
//            'thanksterRecipientID' => $this->thanksterRecipientID,
//            'thanksterUserID' => $this->userId,
//            'thanksterFontColor' => $thanksterFontColor
//        ];

        $this->apiCall('POST','api_projects/selectFontColor',$data);
        return $this;
    }

    /**
     *  Renders a preview of a user's project in an iframe.
     *
     * When this URL is used in an iframe, a preview of the user's card is returned. This may be useful for display in a user's dashboard, during their checkout on your site, etc.
     */

    public function renderProjectPreview($thanksterProjectID)
    {
        return file_get_contents('http://www.thankster.com/api/v1/api_projects/renderProjectPreview?api_key='.$this->API_KEY.'&thanksterProjectID='.$thanksterProjectID);
    }

    /**
     * Once a project is complete and you are ready to order it, this call will place the order in Thankster's systems and return an Order ID back for your records.
     * This Order ID must be used in the setPartnerOrderID before calling the approveForPrinting method.
     */
    public function orderProject($orderData)
    {
        $this->apiCall('POST','api_projects/orderProject',$orderData);
        return $this;
    }

    /**
     *  Submit your Order ID to Thankster so orders may be cross referenced.
     *
     * This call MUST be placed before the approveForPrinting call can be made.
     */

    public function setPartnerOrderID($orderData)
    {
        $this->apiCall('POST','api_projects/setPartnerOrderID',$orderData);
        return $this;
    }

    /**
     * Used to notify Thankster that an order may be printed and mailed.
     */

    public function approveForPrinting($orderData)
    {
        $this->apiCall('POST','api_projects/approveForPrinting',$orderData);
        return $this;
    }

    /**
     *  Imports a list of contacts into a user's addressbook on Thankster.com.
     *
     * The user must first be created or verified by using the findOrCreateUserByEmail call. This ensures there is a user to import the contacts to.
     *
     * Each time this call is executed, the entries will be added to the user's addressbook. Sending the same entries again will result in duplicates in the user's addressbook.
     */
    public function importAddressbook($addressdata)
    {
//        [
//            'userEmail' => $userEmail,
//            'entries' => $entries,
//            'thanksterOrderID' => $this->thanksterOrderID,
//        ];

        $this->apiCall('POST','api_users/importAddressbook',json_encode($addressdata));
        return $this;
    }

    /**
     * retrieve the list of cards available from Thankster.
     */
    public function listCards(int $thanksterCategoryID)
    {
        $postData['thanksterCategoryID'] = $thanksterCategoryID;

        $this->apiCall('POST','api_projects/listCards',$postData);
        return $this;
    }

    /**
     * retrieve the list of card categories available from Thankster.
     */
    public function listCardsCategories()
    {
        $this->apiCall('GET','api_projects/listCardsCategories');
        return $this;
    }

    /**
     * retrieve the list of font categories available from Thankster
     */
    public function listFonts()
    {
        $this->apiCall('GET','api_projects/listFonts');
        return $this;
    }

    /**
     * This creates a new Project with a single card in it.
     * The template ID provides the design of the card and defaults for the project
     */
    public function createQuickProject($postData)
    {
        $this->apiCall($postData,'POST','api_projects/createQuickProject');
        return $this;
    }


    public function getResponse()
    {
        return $this->responseData;
    }

    public function getRawResponse()
    {
        return $this->rawResponse;
    }


    private function apiCall(string $method, string $url,$postdata = [],array $extraData = [])
    {

        $params = [
            'query' => [
                'api_key' => $this->API_KEY,
            ],
        ];

        // POST REQUEST
        if(!empty($postdata))
        {
            $headers['content-type'] = 'application/json';
            $params['form_params'] = $postdata;

            $params['debug'] = $this->DEBUG;

            $response = $this->httpClient->request($method, $url, $params, $headers);

        }
        else
        {
            //GET REQUEST
            $extraData = array_merge($extraData,$params);

            $extraData['debug'] = $this->DEBUG;

            $response = $this->httpClient->request($method, $url, $extraData);

        }


        $this->rawResponse = $response;
        $this->responseData = $response->getBody()->getContents();
        if(!empty($this->responseData)) {
            $this->parseResponse();
        }
    }

    private function parseResponse()
    {
        $this->responseData = json_decode($this->responseData);
    }

}


