<?php

function getAISuggestion($pendingTasks, $completedTasks, $productivity){

    if($pendingTasks >= 5){
        return "⚠️ You have many pending tasks. Focus on high priority tasks first.";
    }

    if($productivity >= 80){
        return "🔥 Amazing productivity! Keep maintaining consistency.";
    }

    if($completedTasks >= 5){
        return "✅ Great work! You are completing tasks consistently.";
    }

    if($pendingTasks == 0){
        return "🎉 Excellent! All tasks completed.";
    }

    return "📚 Try using Pomodoro technique for better focus.";
}

?>