<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {do expression}
 */
class DoNode extends StatementNode
{
	public ExpressionNode $expression;


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$node = new static;
		$node->expression = $tag->parser->parseExpression();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'%node %line;',
			$this->expression,
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expression;
	}
}
