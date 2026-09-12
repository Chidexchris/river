#!/bin/bash

# Git All-in-One Script
# Usage: ./git-all.sh "commit message"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if commit message is provided
if [ $# -eq 0 ]; then
    print_error "Please provide a commit message!"
    echo "Usage: ./git-all.sh \"your commit message\""
    exit 1
fi

COMMIT_MESSAGE="$1"

print_status "Starting Git All-in-One operation..."

# Check if we're in a git repository
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    print_error "Not in a git repository!"
    exit 1
fi

# Check current branch
CURRENT_BRANCH=$(git branch --show-current)
print_status "Current branch: $CURRENT_BRANCH"

# Check if there are any changes to commit
if git diff-index --quiet HEAD --; then
    print_warning "No changes to commit!"
    exit 0
fi

# Step 1: Git Add
print_status "Adding all files..."
if git add .; then
    print_success "Files added successfully"
else
    print_error "Failed to add files"
    exit 1
fi

# Step 2: Git Commit
print_status "Committing changes with message: '$COMMIT_MESSAGE'"
if git commit -m "$COMMIT_MESSAGE"; then
    print_success "Changes committed successfully"
else
    print_error "Failed to commit changes"
    exit 1
fi

# Step 3: Git Push
print_status "Pushing to remote repository..."
if git push origin "$CURRENT_BRANCH"; then
    print_success "Changes pushed successfully to $CURRENT_BRANCH"
else
    print_error "Failed to push changes"
    print_warning "You may need to set up remote or check your credentials"
    exit 1
fi

print_success "Git All-in-One operation completed successfully! 🎉"
print_status "Summary:"
echo "  - Added all files"
echo "  - Committed with message: '$COMMIT_MESSAGE'"
echo "  - Pushed to branch: $CURRENT_BRANCH"
