"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import type { ReactNode } from "react";
import { MoonStar, SunMedium } from "lucide-react";

import { dashboardPathForRole, useMockLms } from "@/providers/mock-lms-provider";
import { useThemeMode } from "@/providers/theme-provider";
import type { HeroStat, Panel } from "@/lib/constants/site-data";

const shellFrame = "mx-auto w-full max-w-[1840px] px-4 sm:px-6 lg:px-8";

type ShellProps = {
  children: ReactNode;
};

type HeroProps = {
  eyebrow: string;
  title: string;
  description: string;
  stats: HeroStat[];
  ctaLabel?: string;
  ctaHref?: string;
  secondaryLabel?: string;
  secondaryHref?: string;
};

export function SiteShell({ children }: ShellProps) {
  const pathname = usePathname();
  const { currentUser, isAuthenticated } = useMockLms();
  const { mounted, theme, toggleTheme } = useThemeMode();

  const isHome = pathname === "/";
  const isDashboard = pathname.startsWith("/admin") || pathname.startsWith("/teacher") || pathname.startsWith("/student");

  if (isHome || isDashboard) {
    return <>{children}</>;
  }

  const dashboardHref = isAuthenticated ? dashboardPathForRole(currentUser?.role) : "/login";

  return (
    <div className="relative min-h-screen bg-[#f2f3f7] text-[#0f172a] dark:bg-[#0b1220] dark:text-[#f8fafc]">
      <div className="bg-[#0b1220] text-white dark:bg-[#020617]">
        <div className="mx-auto flex w-full max-w-[1240px] items-center justify-center gap-8 px-4 py-3 text-sm font-semibold">
          <Link href="/" className="border-b border-transparent transition hover:border-white/60">For Learners</Link>
          <Link href="/solutions" className="border-b border-transparent transition hover:border-white/60">For Teams</Link>
          <Link href="/about" className="border-b border-transparent transition hover:border-white/60">For Institutes</Link>
          <Link href="/features" className="border-b border-transparent transition hover:border-white/60">For Enterprise</Link>
        </div>
      </div>

      <header className="sticky top-0 z-40 border-b border-[#d6dbe4] bg-white/95 backdrop-blur dark:border-white/10 dark:bg-[#0f172a]/95">
        <div className="mx-auto flex w-full max-w-[1240px] items-center justify-between gap-3 px-4 py-3">
          <div className="flex items-center gap-6">
            <Link href="/" className="flex items-center gap-3">
              <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-[linear-gradient(135deg,#1A1A2E_58%,#E8A020)] text-sm font-bold text-white">
                SL
              </div>
              <span className="text-2xl font-bold leading-none text-[#1A1A2E] dark:text-[#f8fafc]">Smart LMS</span>
            </Link>
            <div className="hidden items-center gap-4 text-sm font-medium text-[#334155] md:flex dark:text-slate-300">
              <Link href="/catalog">Explore</Link>
              <Link href="/pricing">Pricing</Link>
              <Link href="/features">Features</Link>
            </div>
          </div>

          <div className="flex items-center gap-3 text-sm font-semibold">
            <button
              type="button"
              onClick={toggleTheme}
              className="rounded-lg border border-[#1A1A2E] px-3 py-2 text-[#1A1A2E] transition hover:bg-[#1A1A2E]/10 dark:border-[#E8A020] dark:text-[#E8A020]"
              aria-label={mounted ? `Switch to ${theme === "light" ? "dark" : "light"} mode` : "Toggle theme"}
            >
              {mounted && theme === "dark" ? <SunMedium className="h-4 w-4" /> : <MoonStar className="h-4 w-4" />}
            </button>
            <Link href="/login" className="text-[#1A1A2E] dark:text-[#f8fafc]">Log In</Link>
            <Link href={dashboardHref} className="rounded-lg border border-[#1A1A2E] px-4 py-2 text-[#1A1A2E] dark:border-[#E8A020] dark:text-[#E8A020]">
              Open Dashboard
            </Link>
          </div>
        </div>
      </header>

      <main>{children}</main>
      <SiteFooter />
    </div>
  );
}

export function HeroSection({
  eyebrow,
  title,
  description,
  stats,
  ctaLabel,
  ctaHref,
  secondaryLabel,
  secondaryHref
}: HeroProps) {
  return (
    <section className={`${shellFrame} pb-12 pt-12 md:pb-20 md:pt-16`}>
      <div className="grid gap-10 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
        <div className="space-y-6">
          <p className="inline-flex rounded-full border border-foreground/10 bg-white/70 px-4 py-2 text-xs font-semibold uppercase tracking-[0.26em] text-muted-foreground shadow-soft dark:border-white/8 dark:bg-white/5">
            {eyebrow}
          </p>
          <h1 className="max-w-4xl font-serif text-5xl leading-[0.95] text-balance md:text-7xl">
            {title}
          </h1>
          <p className="max-w-2xl text-lg leading-8 text-muted-foreground md:text-xl">
            {description}
          </p>

          {(ctaLabel || secondaryLabel) && (
            <div className="flex flex-wrap gap-3 pt-2">
              {ctaLabel && ctaHref ? (
                <Link href={ctaHref} className="rounded-full bg-foreground px-5 py-3 text-sm font-semibold text-background shadow-soft transition hover:translate-y-[-1px]">
                  {ctaLabel}
                </Link>
              ) : null}
              {secondaryLabel && secondaryHref ? (
                <Link href={secondaryHref} className="rounded-full border border-foreground/15 px-5 py-3 text-sm font-semibold transition hover:border-foreground/35">
                  {secondaryLabel}
                </Link>
              ) : null}
            </div>
          )}
        </div>

        <div className="rounded-[2rem] border border-white/65 bg-white/80 p-5 shadow-glow backdrop-blur">
          <div className="grid gap-4 sm:grid-cols-2">
            {stats.map((stat) => (
              <div key={stat.label} className="rounded-[1.5rem] border border-black/5 bg-background/90 p-5">
                <p className="text-xs uppercase tracking-[0.22em] text-muted-foreground">{stat.label}</p>
                <p className="mt-3 font-serif text-4xl">{stat.value}</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

export function FeaturePanel({ panels }: { panels: Panel[] }) {
  return (
    <section className={`${shellFrame} py-12 md:py-24`}>
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {panels.map((panel) => (
          <div
            key={panel.title}
            className={`group relative overflow-hidden rounded-[2rem] border p-8 transition hover:shadow-soft ${
              panel.tone === "accent"
                ? "border-[#E8A020]/20 bg-[#1A1A2E] text-white"
                : panel.tone === "warm"
                  ? "border-orange-300/35 bg-orange-50"
                  : "border-foreground/5 bg-white dark:bg-white/5"
            }`}
          >
            <div className="mb-6 flex h-12 w-12 items-center justify-center rounded-2xl bg-foreground/5 font-serif text-xl text-foreground transition group-hover:scale-110">
              {panel.title.slice(0, 1)}
            </div>
            <h3 className="font-serif text-2xl">{panel.title}</h3>
            <p className={`mt-4 ${panel.tone === "accent" ? "text-white/75" : "text-muted-foreground"}`}>
              {panel.body}
            </p>
          </div>
        ))}
      </div>
    </section>
  );
}

export const PanelGrid = FeaturePanel;

export function SiteFooter() {
  return (
    <footer className="bg-[#0F172A] text-white">
      <div className="mx-auto grid w-full max-w-[1240px] gap-8 px-4 py-12 md:grid-cols-2 lg:grid-cols-4">
        <div>
          <p className="text-lg font-bold">Smart LMS</p>
          <p className="mt-3 text-sm text-slate-300">Course delivery, live classes, AI assessments, certificates, and reporting in one platform.</p>
        </div>
        <div className="space-y-2 text-sm text-slate-300">
          <p className="font-semibold text-white">Platform</p>
          <Link className="block hover:text-[#E8A020]" href="/features">Features</Link>
          <Link className="block hover:text-[#E8A020]" href="/pricing">Pricing</Link>
          <Link className="block hover:text-[#E8A020]" href="/catalog">Catalog</Link>
        </div>
        <div className="space-y-2 text-sm text-slate-300">
          <p className="font-semibold text-white">Company</p>
          <Link className="block hover:text-[#E8A020]" href="/about">About</Link>
          <Link className="block hover:text-[#E8A020]" href="/contact">Contact</Link>
          <Link className="block hover:text-[#E8A020]" href="/demo">Book Demo</Link>
        </div>
        <div className="space-y-2 text-sm text-slate-300">
          <p className="font-semibold text-white">Legal</p>
          <Link className="block hover:text-[#E8A020]" href="/privacy">Privacy</Link>
          <Link className="block hover:text-[#E8A020]" href="/terms">Terms</Link>
        </div>
      </div>
      <div className="border-t border-white/10">
        <div className="mx-auto flex w-full max-w-[1240px] items-center justify-between gap-4 px-4 py-5 text-sm text-slate-300">
          <p>© 2026 Smart LMS. All rights reserved.</p>
          <p>Designed for institutes, teachers, and learners.</p>
        </div>
      </div>
    </footer>
  );
}
