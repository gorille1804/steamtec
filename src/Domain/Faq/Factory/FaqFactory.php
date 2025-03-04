<?php

namespace Domain\Faq\Factory;

use Domain\Faq\Data\Contract\CreateFaqRequest;
use Domain\Faq\Data\Model\Faq;
use Domain\Faq\Data\ObjectValue\FaqId;
use Domain\Faq\Data\Contract\UpdateFaqRequest;
class FaqFactory
{
    /**
     * Creates a new FAQ instance from the creation request.
     *
     * @param CreateFaqRequest $request
     * @return Faq
     */
    public static function make(CreateFaqRequest $request): Faq
    {
        return new Faq(
            FaqId::make(),
            $request->question,
            $request->answer,
            $request->isActive
        );
    }

    public static function update(Faq $faq, UpdateFaqRequest $request): Faq
    {
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->isActive = $request->isActive;

        return $faq;
    }

    public static function makeFromFaq(Faq $faq): UpdateFaqRequest
    {
        $request = new UpdateFaqRequest();
        $request->question = $faq->question;
        $request->answer = $faq->answer;
        $request->isActive = $faq->isActive;

        return $request;
    }
}