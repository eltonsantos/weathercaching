<?php
class ObsController extends AppController {
    var $scaffold;
    public function add(){
        if(empty($_REQUEST))
        {
            $this->redirect(array('action' => 'manual_ob_record'));
        }
        else
        {
            try
            {
                $temp = isset($_REQUEST['temp']) && is_numeric($_REQUEST['temp'])? $_REQUEST['temp'] : null;
                $humidity = isset($_REQUEST['humidity']) && is_numeric($_REQUEST['humidity'])? $_REQUEST['humidity'] : null;
                $sensor_id = isset($_REQUEST['sensor_id']) && is_numeric($_REQUEST['sensor_id'])? $_REQUEST['sensor_id'] : null;
                if($sensor_id && ($temp || $humidity ))
                {
                    $this->Ob->set('temp', $temp);
                    $this->Ob->set('sensor_id', $sensor_id);
                    $this->Ob->set('humidity', $humidity);
                    $ob = $this->Ob->save($temp);
                }
            }
            catch (Exception $e)
            {
                $this->Session->setFlash("");
            }
            $this->redirect(array('action' => 'user_feedback', $ob['Ob']['id'] ? $ob['Ob']['id'] : '0' ));
        }
    }

    public function manual_ob_record(){

    }

    public function updateOb(){
        $geocode = !empty($_POST['geocode']) ? $_POST['geocode'] : null;
        $ob_id = isset($_POST['ob_id']) && is_numeric($_POST['ob_id']) ? $_POST['ob_id'] : null;
        $mood = isset($_POST['mood']) && is_numeric($_POST['mood']) ? $_POST['mood'] : null;
        $guess_temp = isset($_POST['guessTemp']) && is_numeric($_POST['guessTemp']) ? $_POST['guessTemp'] : null;
        list($lat, $lon) = explode(',', $geocode);
        $updated = false;
        if (is_numeric($lat) && is_numeric($lon))
        {
            $this->Ob->set('id', $ob_id);
            $this->Ob->set('lat', $lat);
            $this->Ob->set('lon', $lon);
            $this->Ob->save();
            $updated = true;
        }
        if(!is_null($mood))
        {
            $this->Ob->Mood->set('ob_id', $ob_id);
            $this->Ob->Mood->set('mood', $mood);
            $this->Ob->Mood->save();
            $updated = true;
        }
        if(!is_null($guess_temp))
        {
            $this->Ob->ObGuess->set('ob_id', $ob_id);
            $this->Ob->ObGuess->set('temp', $guess_temp);
            $updated = true;
        }
        if($updated)
        {
            $this->Session->setFlash("Observation location updated");
        }
        else
        {
            $this->Session->setFlash("No/invalid location details sent");
        }
        $this->redirect(array('action' => 'index'));
    }

    public  function  allJSON(){
        Configure::write('debug', 0);
        header('Content-type: application/json');
        $obs = $this->Ob->find('all');
        $json = array(
            'data' => array(
                'time' => "now!",
                'locations' => array()
            )
        );
        for ($i = 0; $i < count($obs); $i++)
        {
            $json['data']['locations'][] = array(
                'name' => "sensor" . $obs[$i]['Ob']['sensor_id'],
                'lat' => $obs[$i]['Ob']['lat'],
                'lon' => $obs[$i]['Ob']['lon'],
                'values' => array(
                    'temperature' => $obs[$i]['Ob']['temp'],
                    'humidity' => $obs[$i]['Ob']['humidity']
                ),
                'observed' => $obs[$i]['Ob']['created']
            );
        }
        die(json_encode($json));
    }

    public function user_feedback($ob_id){
        $this->layout = "simple";
        $this->set('ob_id', $ob_id);
    }

    public function sensor_info($sensor_id){
        $obs = $this->Ob->find('all', array('condition'=>array('Sensor'=>$sensor_id)));
        debug($obs);
        die();

    }
}