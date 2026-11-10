# WCAG 2.2 AA review — 10 November 2026

Scope: the DSV text-page template, start page, news/helpdesk/status/welcome/people cards, shared header/navigation, breadcrumbs, footer, FAQ, image and video blocks. This is a source review and remediation record, not a conformance claim. No browser or screen reader was available for this review, and database access prevented application rendering.

## Changes

| Criteria | Remediation |
| --- | --- |
| 1.1.1 Non-text Content | Page images use their CMS alt field; decorative status arrows are hidden from assistive technology. Informative images still require an author-supplied description. |
| 1.3.1 Info and Relationships; 2.4.6 Headings and Labels | Start page has a main heading; existing cards retain named sections; FAQ questions are headings; search has a programmatic label. |
| 1.3.2 Meaningful Sequence; 2.4.3 Focus Order | Removed CSS column reordering so desktop visual order follows the document order. |
| 1.4.3 Contrast; 1.4.11 Non-text Contrast | Dark search badges and breadcrumb text use higher-contrast colours. Search input boundaries are stronger in both themes. |
| 1.4.10 Reflow | Breadcrumbs wrap and video no longer forces a 640px minimum width. |
| 1.4.13 Content on Hover or Focus | Shared tooltips use the existing hoverable, focusable-trigger, Escape-dismissible implementation. |
| 2.1.1 Keyboard; 2.4.7 Focus Visible; 2.4.11 Focus Not Obscured (Minimum) | Native video controls replace unnamed custom controls; card controls have explicit focus outlines; highlighted search results scroll into view. |
| 2.4.1 Bypass Blocks | Both pages use the layout's navigation section, enabling the skip link and keeping header/navigation outside main content. |
| 2.5.8 Target Size (Minimum) | News admin link has a 44px target; standalone text links and breadcrumb links have at least 24px height. Other card buttons already have larger targets. |
| 4.1.2 Name, Role, Value | FAQ buttons expose expanded state and associated panels. Search exposes active option and selection; language dropdown exposes state and supports Escape. Banner IDs are unique. |
| 4.1.3 Status Messages | Search result count and selected-person details have live announcements. |

## Verification

- Production Vite build passed; JavaScript syntax check passed.
- Thirteen affected Blade templates compiled and passed PHP syntax checks without booting the application.
- `git diff --check` passed.
- Calculated opaque palette samples: light text/muted/link colours on `#f3f6fb` are 13.18:1, 5.42:1, and 6.27:1; dark equivalents on `#302941` are 12.42:1, 7.34:1, and 8.22:1. Primary button pairs are 6.45:1 and 7.99:1. These samples do not establish contrast for every rendered gradient, translucent surface, or CMS colour.
- `HomeLocalizationTest` could not boot: Statamic's configured MariaDB connection was denied by the sandbox. No assertions ran.

## Required before claiming conformance

1. Test actual English and Swedish pages with keyboard only and VoiceOver/Safari or NVDA/Firefox. Exercise mobile navigation, nested dropdowns, tooltips, FAQ, search loading/empty/results/selection, announcement dismissal, and video controls. Verify focus is visible and unobscured throughout.
2. Run an automated accessibility scan on rendered pages and all interactive states in both themes. Check duplicate IDs, accessible names, landmarks, CMS markup, and actual composited contrast.
3. Test 200% text resize, 400% zoom / 320 CSS pixel reflow, and WCAG text-spacing overrides. Check long titles, email addresses, tables, images and translated navigation for clipping or horizontal page scrolling.
4. Review authored content: meaningful image alternatives, heading hierarchy, descriptive links, language changes, table header associations, document titles and downloadable document accessibility.
5. Review every video for captions (1.2.2), audio description or media alternatives (1.2.3), and audio description where required at AA (1.2.5). The existing block still points to `test.mp4`; no captions, transcript or audio-description assets were supplied, so these requirements remain unverified.
6. Assess other applicable WCAG 2.2 A/AA criteria against real content and workflows, including authentication if it is part of the evaluated site. Criteria absent from this record have not been certified as passing or inapplicable.
