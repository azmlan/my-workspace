# Claude Code — Prompt Flow
# This is the full prmpt i can reuse
Read CLAUDE.md. Then read @docs/phases.md and continue from the first unchecked task/Phase
Before writing anything, read @docs/schema.md fully, then proceed.
Before proceeding to the target phase , read @docs/hosting-constraints.md, then proceed.

## After Each Task Completes
```
Run php artisan route:list and php artisan test. If both pass, check off that task in @docs/phases.md and continue working , if no promplems found 
```

## To Catch Drift
```
Stop. Before writing the next file, tell me which CLAUDE.md rule covers what you are about to do.
```

## When Something Feels Wrong
```
Stop. Explain what you just built and how it maps to @docs/schema.md.
```

## End of Phase
```
Phase X is complete. All tasks are checked. Read @docs/phases.md and confirm everything in Phase X is done before we move to Phase X+1.
```

---------------------

---

## Start of Every Session
```
Read CLAUDE.md. Then read @docs/phases.md and continue from the first unchecked task in Phase X.
```

---

## Before Touching Migrations or Models
```
Before writing anything, read @docs/schema.md fully, then proceed.
```

---

## Before Touching Queue, Cache, or Deployment
```
Before proceeding to the target phase , read @docs/hosting-constraints.md, then proceed.
```

---

## After Each Task Completes
```
Run php artisan route:list and php artisan test. If both pass, check off that task in @docs/phases.md and continue working , if no promplems found 
```

---

## After /clear — Re-Onboard
```
Read CLAUDE.md. Read @docs/phases.md — Phase X is in progress, checked tasks are done. Next unchecked task is [task]. Read @docs/schema.md before writing anything.
```

---


## To Catch Drift
```
Stop. Before writing the next file, tell me which CLAUDE.md rule covers what you are about to do.
```

---


## When Something Feels Wrong
```
Stop. Explain what you just built and how it maps to @docs/schema.md.
```

---

## End of Phase
```
Phase X is complete. All tasks are checked. Read @docs/phases.md and confirm everything in Phase X is done before we move to Phase X+1.
```
