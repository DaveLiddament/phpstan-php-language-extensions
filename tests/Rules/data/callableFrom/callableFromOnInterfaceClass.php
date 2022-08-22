<?php

namespace CallableFromOnInterfaceClass;

use DaveLiddament\PhpLanguageExtensions\CallableFrom;

#[CallableFrom(MessageSendingService::class)]
interface MessageSender
{
    public function sendMessage(): void;
}


class PhpMailerMessageSender implements MessageSender
{
    public function sendMessage(): void
    {
    }
}


class MessageSendingService
{
    public function __construct(public MessageSender $messageSender) {}

    public function sendMessage(): void
    {
        $this->messageSender->sendMessage(); // OK
    }
}

class Foo
{
    public function __construct(public MessageSender $messageSender) {}

    public function sendMessage(): void
    {
        $this->messageSender->sendMessage(); // ERROR Foo is not a callableFrom of MessageSender
    }
}
