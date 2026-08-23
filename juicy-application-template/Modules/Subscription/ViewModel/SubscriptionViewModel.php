<?php

namespace Modules\Subscription\ViewModel;

use Modules\Package\Service\PackageService;


class SubscriptionViewModel
{
  public function activePackages()
  {
    return (new PackageService())->active();
  }
}
