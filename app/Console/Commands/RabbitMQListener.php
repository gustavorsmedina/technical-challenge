<?php

namespace App\Console\Commands;

use App\Brokers\{RabbitMQ};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RabbitMQListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "rabbitmq:listener";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Listen for messages from RabbitMQProducer and process them";

    private RabbitMQ $rabbitMQ;

    public function __construct(RabbitMQ $rabbitMQ)
    {
        parent::__construct();
        $this->rabbitMQ = $rabbitMQ;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($message) {
            $data = json_decode($message->body, true);
            echo "\n json_decode: ", json_encode($data), "\n";
            echo "---------------------------------------------\n";
            echo " [x] Received ", $message->body, "\n";
            echo "Sending notification...\n";
            echo "---------------------------------------------\n";

            $response = Http::post("https://util.devi.tools/api/v1/notify");

            if ($response->successful()) {
                echo "---------------------------------------------\n";
                echo " [x] Transaction ", $message->body, "\n";
                echo "Notification sent successfully...\n";
                echo "---------------------------------------------\n";
            } else {
                echo "---------------------------------------------\n";
                echo " [x] Transaction ", $message->body, "\n";
                echo "Fail to send notification...\n";
                echo "---------------------------------------------\n";
                $this->rabbitMQ->send("notifications", $data);
            }
        };

        $this->rabbitMQ->listener("notifications", $callback);
        $this->rabbitMQ->wait();
    }

}
