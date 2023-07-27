<?php

namespace App;

class Subject implements \SplSubject {
    /**
    * @var int For the sake of simplicity, the Subject's state, essential to
    * all subscribers, is stored in this variable.
    */
    public $state;

    /**
    * @var \SplObjectStorage List of subscribers. In real life, the list of
    * subscribers can be stored more comprehensively (categorized by event
    * type, etc.).
    */
    private $observers;

    public function __construct(){
        $this->observers = new \SplObjectStorage();
    }

    /**
    * The subscription management methods.
    */
    public function attach(\SplObserver $observer): void{
        echo "Subject: Attached an observer.\n";
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer): void
    {
        $this->observers->detach($observer);
        echo "Subject: Detached an observer.\n";
    }

    /**
    * Trigger an update in each subscriber.
    */
    public function notify(): void {
        echo "Subject: Notifying observers...\n";
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
