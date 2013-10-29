<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2010 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\Controller\Converter;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

interface ConverterInterface
{
    /**
     * Converts an id in the matching entity
     *
     * @param integer $id
     *
     * @throws NotFoundHttpException
     */
    public function convert($id);
}