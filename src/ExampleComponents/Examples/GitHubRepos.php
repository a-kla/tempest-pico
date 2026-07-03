<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\ExampleComponents\GitHubRepos;

use function AKl\Tempest_HtmlView\content;

return content(new GitHubRepos());
