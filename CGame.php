<?php

include 'CFrame.php';
include 'CLastFrame.php';

/* Game manager */
class CGame {
    
    private $iScore = 0; 
    private $aFrames = array();
    
    const  NUMBER_OF_FRAMES = 10;

    function fRoll($iPins) {
        $iNumberOfFrames = count($this->aFrames);
        if(!$iNumberOfFrames || !$this->aFrames[$iNumberOfFrames-1]->fNextRollDue()) {
            if(self::NUMBER_OF_FRAMES <= $iNumberOfFrames) {
                throw new Exception('The game has finished.'); 
            } elseif(self::NUMBER_OF_FRAMES - 1 == $iNumberOfFrames) {
                $this->aFrames[] = new CLastFrame();
            } else {
                $this->aFrames[] = new CFrame();
            }
            $iNumberOfFrames++;
        }

        // add the roll points to the frame
        $this->aFrames[$iNumberOfFrames-1]->fHandleRoll($iPins);

        // add points for bonus from previous frame        
        if($iNumberOfFrames > 1 && $this->aFrames[$iNumberOfFrames-2]->fGetBonusDue()) {  
           $this->aFrames[$iNumberOfFrames-2]->fHandleBonus($iPins); 
        }

        // add points for bonus from one before previous frame
        if($iNumberOfFrames > 2 && $this->aFrames[$iNumberOfFrames-3]->fGetBonusDue()) {  
           $this->aFrames[$iNumberOfFrames-3]->fHandleBonus($iPins); 
        }

        return $this->fCountScore();
    }

    function fGetScore() {
        return $this->fCountScore();
    } 

    private function fCountScore() {
        $this->iScore = 0;
        foreach($this->aFrames as $iKey => $oFrame) {
            $this->iScore += $oFrame->fGetFrameScore();
        } 
        return $this->iScore;
    } 

} 

?>
