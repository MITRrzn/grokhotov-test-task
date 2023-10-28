<?php

namespace App\Service;

use App\Entity\UserMessage;
use App\Repository\UserMessageRepository;

class FeedbackService
{

    public function __construct(private readonly UserMessageRepository $userMessageRepo)
    {
    }

    /**
     * @throws \Exception
     */
    public function saveUserMessage(UserMessage $message): void
    {
        $this->userMessageRepo->save($message, true);
    }

}