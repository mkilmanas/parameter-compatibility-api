<?php

namespace spec\App\Exception;

use App\Exception\EntityNotFoundException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntityNotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(EntityNotFoundException::class);
    }

    function it_is_a_kind_of_invalid_argument_exeption()
    {
        $this->shouldHaveType(\InvalidArgumentException::class);
    }
}
