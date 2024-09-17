<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Resolver;

use App\Dto\NoticeListInputFiltersDto;
use App\Entity\NoticeStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * NoticeListInputFiltersDtoResolver class.
 */
class NoticeListInputFiltersDtoResolver implements ValueResolverInterface
{
    /**
     * Returns the possible value(s).
     *
     * @param Request          $request  HTTP Request
     * @param ArgumentMetadata $argument Argument metadata
     *
     * @return iterable Iterable
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !is_a($argumentType, NoticeListInputFiltersDto::class, true)) {
            return [];
        }

        $categoryId = $request->query->get('categoryId');
        $tagId = $request->query->get('tagId');
        $statusId = $request->query->get('statusId', NoticeStatus::STATUS_ACTIVE);

        return [new NoticeListInputFiltersDto($categoryId, $tagId, $statusId)];
    }
}
