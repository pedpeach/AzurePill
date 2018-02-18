<?php
    //save file image
    $predictionKey="{Prediction Key}";
    $projectId="{Project Id}";
	$uploadDir="upload/";
	$img=$_POST['fileImage'];
	$img=str_replace('data:image/png;base64,','',$img);
	$img=str_replace(' ','+',$img);
	$data=base64_decode($img);

	//set name file image
    $nameFile=mktime().".png";
	$file=$uploadDir.$nameFile;
	$success=file_put_contents($file,$data);

    //get url of image
    $pageURL='http';
    if($_SERVER["HTTPS"]=="on"){
        $pageURL.="s";
    }
    $pageURL.='://'.$_SERVER["SERVER_NAME"].'/'.'upload/'.$nameFile;;

    //set header curl
    $arrHeader=array();
    $arrHeader[]="Content-Type: application/json";
    $arrHeader[]="Prediction-Key: ".$predictionKey;
    $url='https://southcentralus.api.cognitive.microsoft.com/customvision/v1.1/Prediction/'.$projectId.'/url';
    $ch=curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url); 
    curl_setopt($ch,CURLOPT_HEADER,false); 
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$arrHeader);
    $arrPostData=array();
    $arrPostData['Url']=$pageURL;
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($arrPostData));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE); 
    $head=curl_exec($ch);
    $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE); 
    curl_close($ch);

    //send json
    $response = json_decode($head,true);
    echo json_encode($response["Predictions"]);
?>