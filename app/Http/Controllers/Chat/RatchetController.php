<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use App\ChatMember;
use App\ChatMemberStatus;
use Exception;
use React\EventLoop\LoopInterface;
use React\EventLoop\TimerInterface;
use SplObjectStorage;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class RatchetController extends Controller implements MessageComponentInterface
{
    private $loop;
    private $clients;

    /**
     * Store all the connected clients in php SplObjectStorage
     *
     * RatchetController constructor.
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
        $this->clients = new SplObjectStorage;
    }

    /**
     * Store the connected client in SplObjectStorage
     * Notify all clients about total connection
     *
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        echo "Client connected " . $conn->resourceId . " \n";
        $this->clients->attach($conn);
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                "type" => "socket",
                "msg" => "Total Connected: " . count($this->clients)
            ]));
        }
    }

    /**
     * Remove disconnected client from SplObjectStorage
     * Notify all clients about total connection
     *
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Client disconnected " . $conn->resourceId . " \n";
        $chatId = ChatMember::where('resource_id', $conn->resourceId)->first()->chat_id;
        $deletedChatMember = ChatMember::where('resource_id', $conn->resourceId)->delete();

        // Retrieve the list of members and send it back
        $members = ChatMember::with('user')->where('chat_id', $chatId)->get();
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                "type" => "socket",
                "members" => $members,
                "msg" => "Total Connected: " . count($members)
            ]));
        }
    }

    /**
     * Receive message from connected client
     * Broadcast message to other clients
     *
     * @param ConnectionInterface $from
     * @param string $data
     */
    public function onMessage(ConnectionInterface $from, $data)
    {
        $resource_id = $from->resourceId;
        $data = json_decode($data);
        $type = $data->type;
        switch ($type) {
            case 'chat':
                $user_id = $data->user_id;
                $chat_id = $data->chat_id;
                $user_name = $data->user_name;
                $chat_msg = $data->chat_msg;
                $response_from = "<span class='text-success'><b>$user_id. $user_name:</b> $chat_msg <span class='text-warning float-right'>" . date('Y-m-d h:i a') . "</span></span><br><br>";
                $response_to = "<span class='text-info'><b>$user_id. $user_name</b>: $chat_msg <span class='text-warning float-right'>" . date('Y-m-d h:i a') . "</span></span><br><br>";
                // Output
                $from->send(json_encode([
                    "type" => $type,
                    "msg" => $response_from
                ]));

                foreach ($this->clients as $client) {
                    if ($from != $client) {
                        $client->send(json_encode([
                            "type" => $type,
                            "msg" => $response_to
                        ]));
                    }
                }

                // Save to database
                $message = new Message();
                $message->chat_id = $chat_id;
                $message->user_id = $user_id;
                $message->body = $chat_msg;
                $message->save();

                echo "Resource id $resource_id sent $chat_msg \n";
                break;

            case 'open':
                $matchThese = array('chat_id' => $data->chat_id, 'user_id' => $data->user_id);
                ChatMember::updateOrCreate($matchThese, ['status' => ChatMemberStatus::ONLINE, 'resource_id' => $resource_id]);

                // Retrieve the list of members and send it back
                $members = ChatMember::with('user')->where('chat_id', $data->chat_id)->get();

                foreach ($this->clients as $client) {
                    $client->send(json_encode([
                        "type" => "socket",
                        "members" => $members,
                        "msg" => "Total Connected: " . count($members)
                    ]));
                }
                break;

            case 'close':
                $deletedChatMember = ChatMember::where('chat_id', $data->chat_id)
                                                ->where('user_id', $data->user_id)
                                                ->delete();

                // Retrieve the list of members and send it back
                $members = ChatMember::with('user')->where('chat_id', $data->chat_id)->get();
                echo 'Close message called \n';

                foreach ($this->clients as $client) {
                    $client->send(json_encode([
                        "type" => "socket",
                        "members" => $members,
                        "msg" => "Total Connected: " . count($members)
                    ]));
                }
                break;
        }
    }

    /**
     * Throw error and close connection
     *
     * @param ConnectionInterface $conn
     * @param Exception $e
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo $e;
        $deletedChatMember = ChatMember::where('resource_id', $conn->resourceId)->delete();
        $conn->close();
    }
}
