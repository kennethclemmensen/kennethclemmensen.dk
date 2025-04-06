<?php
namespace KC\Core;

/**
 * Provides utility methods for adding actions and filters in WordPress.
 * The class cannot be inherited.
 */
final class PluginService {

	/**
	 * Add an action
	 *
	 * @param string $action the action to add
	 * @param callable $callback the callback to add to the action
	 */
	public function addAction(string $action, callable $callback) : void {
		add_action($action, $callback);
	}

	/**
	 * Add a filter
	 *
	 * @param string $filter the filter to add
	 * @param callable $callback the callback to add to the filter
	 * @param int $priority the priority of the filter
	 */
	public function addFilter(string $filter, callable $callback, int $priority = 10) : void {
		add_filter($filter, $callback, $priority);
	}
}