<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAuditRuleRequest;
use App\Models\AuditRule;
use App\Services\Audits\RuleRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RuleAdminController extends Controller
{
    public function index(RuleRegistry $registry): View
    {
        $registry->sync();

        return view('admin.rules.index', [
            'rules' => AuditRule::query()->orderBy('category')->orderBy('key')->get(),
        ]);
    }

    public function edit(AuditRule $rule): View
    {
        return view('admin.rules.edit', ['rule' => $rule]);
    }

    public function update(UpdateAuditRuleRequest $request, AuditRule $rule): RedirectResponse
    {
        $rule->update([
            'weight' => $request->integer('weight'),
            'severity' => $request->string('severity')->toString(),
            'is_active' => $request->boolean('is_active'),
            'recommendation' => $request->string('recommendation')->toString(),
        ]);

        return redirect()->route('admin.rules.index');
    }
}
