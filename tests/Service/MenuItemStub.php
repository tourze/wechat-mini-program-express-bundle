<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Tests\Service;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

/**
 * 简化的ItemInterface测试桩
 *
 * 注意：Knp\Menu\ItemInterface接口要求setter方法返回ItemInterface，这与项目的静态分析规则冲突。
 * 根据项目规则，当静态分析规则与接口要求冲突时，优先满足接口要求。
 */
final class MenuItemStub implements ItemInterface
{
    /** @var array<string, ItemInterface> */
    private array $children = [];

    public function getChild(string $name): ?ItemInterface
    {
        return $this->children[$name] ?? null;
    }

    public function addChild($child, array $options = []): ItemInterface
    {
        if (is_string($child)) {
            $stub = new self();
            $this->children[$child] = $stub;

            return $stub;
        }

        return $this;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    /** @phpstan-ignore-next-line */
    public function setChildren(array $children): ItemInterface
    {
        $this->children = $children;

        return $this;
    }

    /** @phpstan-ignore-next-line */
    public function removeChild($name): ItemInterface
    {
        if (is_string($name)) {
            unset($this->children[$name]);
        }

        return $this;
    }

    public function getFirstChild(): ItemInterface
    {
        return $this;
    }

    public function getLastChild(): ItemInterface
    {
        return $this;
    }

    public function hasChildren(): bool
    {
        return [] !== $this->children;
    }

    // 基本getter方法
    public function getName(): string
    {
        return 'test';
    }

    /** @phpstan-ignore-next-line */
    public function setName(string $name): ItemInterface
    {
        return $this;
    }

    public function getUri(): ?string
    {
        return null;
    }

    /** @phpstan-ignore-next-line */
    public function setUri(?string $uri): ItemInterface
    {
        return $this;
    }

    public function getLabel(): string
    {
        return '';
    }

    /** @phpstan-ignore-next-line */
    public function setLabel(?string $label): ItemInterface
    {
        return $this;
    }

    public function getAttributes(): array
    {
        return [];
    }

    /** @phpstan-ignore-next-line */
    public function setAttributes(array $attributes): ItemInterface
    {
        return $this;
    }

    public function getAttribute(string $name, $default = null)
    {
        return $default;
    }

    /** @phpstan-ignore-next-line */
    public function setAttribute(string $name, $value): ItemInterface
    {
        return $this;
    }

    // 其他必需方法的简化实现
    public function getLinkAttributes(): array
    {
        return [];
    }

    /** @phpstan-ignore-next-line */
    public function setLinkAttributes(array $linkAttributes): ItemInterface
    {
        return $this;
    }

    public function getLinkAttribute(string $name, $default = null)
    {
        return $default;
    }

    /** @phpstan-ignore-next-line */
    public function setLinkAttribute(string $name, $value): ItemInterface
    {
        return $this;
    }

    public function getLabelAttributes(): array
    {
        return [];
    }

    /** @phpstan-ignore-next-line */
    public function setLabelAttributes(array $labelAttributes): ItemInterface
    {
        return $this;
    }

    public function getLabelAttribute(string $name, $default = null)
    {
        return $default;
    }

    /** @phpstan-ignore-next-line */
    public function setLabelAttribute(string $name, $value): ItemInterface
    {
        return $this;
    }

    public function getChildrenAttributes(): array
    {
        return [];
    }

    /** @phpstan-ignore-next-line */
    public function setChildrenAttributes(array $childrenAttributes): ItemInterface
    {
        return $this;
    }

    public function getChildrenAttribute(string $name, $default = null)
    {
        return $default;
    }

    /** @phpstan-ignore-next-line */
    public function setChildrenAttribute(string $name, $value): ItemInterface
    {
        return $this;
    }

    public function getParent(): ?ItemInterface
    {
        return null;
    }

    /** @phpstan-ignore-next-line */
    public function setParent(?ItemInterface $parent = null): ItemInterface
    {
        return $this;
    }

    public function isRoot(): bool
    {
        return true;
    }

    public function getRoot(): ItemInterface
    {
        return $this;
    }

    public function isChild(): bool
    {
        return false;
    }

    public function getLevel(): int
    {
        return 0;
    }

    public function isLast(): bool
    {
        return true;
    }

    public function isFirst(): bool
    {
        return true;
    }

    public function actsLikeFirst(): bool
    {
        return true;
    }

    public function actsLikeLast(): bool
    {
        return true;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->children);
    }

    public function count(): int
    {
        return count($this->children);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->children[$offset]);
    }

    public function offsetGet($offset): ?ItemInterface
    {
        return $this->children[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if ($value instanceof ItemInterface && null !== $offset) {
            $this->children[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->children[$offset]);
    }

    public function isDisplayed(): bool
    {
        return true;
    }

    /** @phpstan-ignore-next-line */
    public function setDisplay(bool $bool): ItemInterface
    {
        return $this;
    }

    public function getDisplayChildren(): bool
    {
        return true;
    }

    /** @phpstan-ignore-next-line */
    public function setDisplayChildren(bool $bool): ItemInterface
    {
        return $this;
    }

    public function isCurrent(): bool
    {
        return false;
    }

    /** @phpstan-ignore-next-line */
    public function setCurrent(?bool $bool = null): ItemInterface
    {
        return $this;
    }

    public function isCurrentAncestor(): bool
    {
        return false;
    }

    public function getExtra(string $name, $default = null)
    {
        return $default;
    }

    /** @phpstan-ignore-next-line */
    public function setExtra(string $name, $value): ItemInterface
    {
        return $this;
    }

    public function getExtras(): array
    {
        return [];
    }

    /** @phpstan-ignore-next-line */
    public function setExtras(array $extras): ItemInterface
    {
        return $this;
    }

    /** @phpstan-ignore-next-line */
    public function setFactory(FactoryInterface $factory): ItemInterface
    {
        return $this;
    }

    /** @phpstan-ignore-next-line */
    public function reorderChildren(array $order): ItemInterface
    {
        return $this;
    }

    public function copy(): ItemInterface
    {
        return clone $this;
    }
}
