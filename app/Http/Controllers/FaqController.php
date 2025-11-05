<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display the FAQ page
     */
    public function index()
    {
        $faqs = Faq::active()->ordered()->get();
        
        return view('faq.index', compact('faqs'));
    }

    /**
     * Show the form for editing FAQs
     */
    public function edit()
    {
        $faqs = Faq::ordered()->get();
        
        return view('faq.edit', compact('faqs'));
    }

    /**
     * Update or create FAQs
     */
    public function update(Request $request)
    {
        $request->validate([
            'faqs' => 'required|array',
            'faqs.*.id' => 'nullable|exists:faqs,id',
            'faqs.*.question' => 'required|string|max:1000',
            'faqs.*.answer' => 'required|string|max:5000',
            'faqs.*.order' => 'nullable|integer|min:0',
            'faqs.*.is_active' => 'nullable|boolean',
        ]);

        foreach ($request->faqs as $index => $faqData) {
            if (isset($faqData['id']) && $faqData['id']) {
                // Update existing FAQ
                $faq = Faq::findOrFail($faqData['id']);
                $faq->update([
                    'question' => $faqData['question'],
                    'answer' => $faqData['answer'],
                    'order' => $faqData['order'] ?? $index,
                    'is_active' => $faqData['is_active'] ?? true,
                ]);
            } else {
                // Create new FAQ
                Faq::create([
                    'question' => $faqData['question'],
                    'answer' => $faqData['answer'],
                    'order' => $faqData['order'] ?? $index,
                    'is_active' => $faqData['is_active'] ?? true,
                ]);
            }
        }

        return redirect()->route('faq.edit')->with('success', __('FAQs updated successfully!'));
    }

    /**
     * Delete a FAQ
     */
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('faq.edit')->with('success', __('FAQ deleted successfully!'));
    }
}
